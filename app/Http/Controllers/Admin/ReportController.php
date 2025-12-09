<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\StockIngredient;
use App\Models\StockMovement;
use App\Models\Ingredient;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Показать страницу отчетов
     */
    public function index(): View
    {
        $warehouses = Warehouse::orderBy('name')->get();
        return view('admin.reports.index', compact('warehouses'));
    }

    /**
     * Отчет по заказам
     */
    public function orders(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $dateFrom = Carbon::parse($request->date_from)->setTimezone('Asia/Irkutsk')->startOfDay();
        $dateTo = Carbon::parse($request->date_to)->setTimezone('Asia/Irkutsk')->endOfDay();
        
        // Устанавливаем часовой пояс для всех дат
        Carbon::setLocale('ru');

        // Получаем заказы за период
        $orders = Order::with(['user', 'items.product'])
            ->whereBetween('order_date', [$dateFrom, $dateTo])
            ->orderBy('order_date', 'desc')
            ->get();

        // Статистика
        $stats = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'completed_orders' => $orders->where('status', 'Выполнен')->count(),
        ];
        
        // Форматируем телефоны пользователей
        foreach ($orders as $order) {
            if ($order->user && $order->user->phone) {
                $phone = preg_replace('/\D/', '', $order->user->phone);
                if (strlen($phone) == 11 && $phone[0] == '7') {
                    $order->user->formatted_phone = '+7 (' . substr($phone, 1, 3) . ') ' . substr($phone, 4, 3) . '-' . substr($phone, 7, 2) . '-' . substr($phone, 9, 2);
                } elseif (strlen($phone) == 10) {
                    $order->user->formatted_phone = '+7 (' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6, 2) . '-' . substr($phone, 8, 2);
                } else {
                    $order->user->formatted_phone = $order->user->phone;
                }
            }
        }

        $pdf = PDF::loadView('admin.reports.orders-pdf', [
            'orders' => $orders,
            'stats' => $stats,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);

        return $pdf->download('отчет-заказы-' . now()->setTimezone('Asia/Irkutsk')->format('Y-m-d') . '.pdf');
    }

    /**
     * Финансовый отчет
     */
    public function finance(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $dateFrom = Carbon::parse($request->date_from)->setTimezone('Asia/Irkutsk')->startOfDay();
        $dateTo = Carbon::parse($request->date_to)->setTimezone('Asia/Irkutsk')->endOfDay();
        
        // Устанавливаем часовой пояс для всех дат
        Carbon::setLocale('ru');

        // Общая выручка только с принятых заказов
        $totalRevenue = Order::whereBetween('order_date', [$dateFrom, $dateTo])
            ->where('status', 'Принят')
            ->sum('total_amount');

        // Выручка по дням только с принятых заказов
        $revenueByDay = Order::whereBetween('order_date', [$dateFrom, $dateTo])
            ->where('status', 'Принят')
            ->select(DB::raw('DATE(order_date) as date'), DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as orders_count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $stats = [
            'total_revenue' => $totalRevenue,
            'total_orders' => Order::whereBetween('order_date', [$dateFrom, $dateTo])
                ->where('status', 'Принят')
                ->count(),
        ];

        $pdf = PDF::loadView('admin.reports.finance-pdf', [
            'stats' => $stats,
            'revenueByDay' => $revenueByDay,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);

        return $pdf->download('финансовый-отчет-' . now()->setTimezone('Asia/Irkutsk')->format('Y-m-d') . '.pdf');
    }

    /**
     * Отчет по складу
     */
    public function warehouse(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:all,stock,operations',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'warehouse_id' => 'nullable|exists:warehouses,id',
        ]);

        $reportType = $request->report_type;

        // Получаем все склады или конкретный склад
        $selectedWarehouseId = $request->filled('warehouse_id') ? $request->warehouse_id : null;
        
        // Загружаем склады с ингредиентами только если нужен отчет по остаткам или вся информация
        $loadStockIngredients = in_array($reportType, ['all', 'stock']);
        
        if ($selectedWarehouseId) {
            // Если выбран конкретный склад, получаем только его
            $warehousesQuery = Warehouse::where('id', $selectedWarehouseId);
            if ($loadStockIngredients) {
                $warehousesQuery->with(['stockIngredients.ingredient.unitType']);
            }
            $warehouses = $warehousesQuery->orderBy('name')->get();
        } else {
            // Если не выбран, получаем все склады
            $warehousesQuery = Warehouse::query();
            if ($loadStockIngredients) {
                $warehousesQuery->with(['stockIngredients.ingredient.unitType']);
            }
            $warehouses = $warehousesQuery->orderBy('name')->get();
        }

        // Получаем все единицы измерения для поиска базовых единиц
        $units = Unit::with('unitType')->get();

        // Получаем операции по складам с фильтрацией по датам и складу
        // Операции нужны только для типов "all" и "operations"
        $stockMovements = collect();
        $warehouseMovements = [];
        
        if (in_array($reportType, ['all', 'operations'])) {
            $stockMovementsQuery = StockMovement::with(['fromWarehouse', 'toWarehouse', 'ingredient.unitType', 'product']);
            
            $dateFrom = null;
            $dateTo = null;
            
            // Применяем фильтр по датам, если они указаны
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $dateFrom = Carbon::parse($request->date_from)->setTimezone('Asia/Irkutsk')->startOfDay();
                $dateTo = Carbon::parse($request->date_to)->setTimezone('Asia/Irkutsk')->endOfDay();
                
                $stockMovementsQuery->whereBetween('created_at', [$dateFrom, $dateTo]);
            }
            
            // Применяем фильтр по складу, если он выбран
            if ($selectedWarehouseId) {
                $stockMovementsQuery->where(function($q) use ($selectedWarehouseId) {
                    $q->where('from_warehouse_id', $selectedWarehouseId)
                      ->orWhere('to_warehouse_id', $selectedWarehouseId);
                });
            }
            
            $stockMovements = $stockMovementsQuery->orderBy('created_at', 'desc')->get();

            // Группируем операции по складам
            foreach ($warehouses as $warehouse) {
                $warehouseMovements[$warehouse->id] = $stockMovements->filter(function($movement) use ($warehouse) {
                    return $movement->from_warehouse_id == $warehouse->id || $movement->to_warehouse_id == $warehouse->id;
                });
            }
        } else {
            $dateFrom = null;
            $dateTo = null;
        }

        // Статистика: считаем уникальные ингредиенты и общее количество на каждом складе
        // Статистика нужна только для типов "all" и "stock"
        $stats = [
            'total_warehouses' => $warehouses->count(),
            'total_unique_ingredients' => 0,
        ];
        
        $warehouseIngredientCounts = [];
        
        if ($loadStockIngredients) {
            $uniqueIngredientIds = collect();
            
            foreach ($warehouses as $warehouse) {
                $ingredientCount = 0;
                foreach ($warehouse->stockIngredients as $stock) {
                    if ($stock->ingredient) {
                        $uniqueIngredientIds->push($stock->ingredient->id);
                        $ingredientCount++;
                    }
                }
                $warehouseIngredientCounts[$warehouse->id] = $ingredientCount;
            }
            
            $stats['total_unique_ingredients'] = $uniqueIngredientIds->unique()->count();
        }

        // Получаем информацию о выбранном складе для отображения
        $selectedWarehouse = $selectedWarehouseId ? Warehouse::find($selectedWarehouseId) : null;

        $pdf = PDF::loadView('admin.reports.warehouse-pdf', [
            'warehouses' => $warehouses,
            'stats' => $stats,
            'units' => $units,
            'warehouseMovements' => $warehouseMovements,
            'warehouseIngredientCounts' => $warehouseIngredientCounts,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'selectedWarehouse' => $selectedWarehouse,
            'reportType' => $reportType,
        ]);

        $reportTypeNames = [
            'all' => 'вся-информация',
            'stock' => 'остаток-склада',
            'operations' => 'операции'
        ];
        
        $filename = $selectedWarehouse 
            ? 'отчет-склад-' . $reportTypeNames[$reportType] . '-' . $selectedWarehouse->name . '-' . now()->setTimezone('Asia/Irkutsk')->format('Y-m-d') . '.pdf'
            : 'отчет-склад-' . $reportTypeNames[$reportType] . '-' . now()->setTimezone('Asia/Irkutsk')->format('Y-m-d') . '.pdf';
        
        // Очищаем имя файла от недопустимых символов
        $filename = preg_replace('/[^a-zA-Zа-яА-Я0-9\-_\.]/u', '-', $filename);

        return $pdf->download($filename);
    }

    /**
     * Отчет по статистике продукции
     */
    public function products(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $dateFrom = Carbon::parse($request->date_from)->setTimezone('Asia/Irkutsk')->startOfDay();
        $dateTo = Carbon::parse($request->date_to)->setTimezone('Asia/Irkutsk')->endOfDay();
        
        // Устанавливаем часовой пояс для всех дат
        Carbon::setLocale('ru');

        // Статистика по продукции
        $productStats = DB::table('order_items')
            ->join('orders', 'order_items.idOrder', '=', 'orders.id')
            ->join('products', 'order_items.idProduct', '=', 'products.id')
            ->leftJoin('categories', 'products.idCategory', '=', 'categories.id')
            ->whereBetween('orders.order_date', [$dateFrom, $dateTo])
            ->whereIn('orders.status', ['Готов к выдаче', 'Выполнен'])
            ->select(
                'products.id',
                'products.name_product',
                'categories.name_category',
                DB::raw('SUM(order_items.quantity) as total_ordered'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as orders_count')
            )
            ->groupBy('products.id', 'products.name_product', 'categories.name_category')
            ->orderBy('total_ordered', 'desc')
            ->get();

        // Топ продукции
        $topProducts = $productStats->take(10);

        $stats = [
            'total_products' => $productStats->count(),
            'total_ordered' => $productStats->sum('total_ordered'),
            'total_revenue' => $productStats->sum('total_revenue'),
        ];

        $pdf = PDF::loadView('admin.reports.products-pdf', [
            'productStats' => $productStats,
            'topProducts' => $topProducts,
            'stats' => $stats,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);

        return $pdf->download('отчет-продукция-' . now()->setTimezone('Asia/Irkutsk')->format('Y-m-d') . '.pdf');
    }
}

