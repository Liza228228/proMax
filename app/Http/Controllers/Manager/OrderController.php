<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items.product']);

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('login', 'like', "%{$search}%");
                  });
            });
        }

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Фильтр по дате
        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->orderBy('order_date', 'desc')->paginate(15)->withQueryString();
        
        $statuses = ['Создан', 'Принят', 'Готов к выдаче', 'Выполнен'];
        
        return view('manager.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        // Проверяем, что заказ не в статусе "Создан"
        if ($order->status === 'Создан') {
            return redirect()->route('manager.orders.index')
                ->with('error', 'Нельзя изменить статус заказа, который еще не принят.');
        }

        $request->validate([
            'status' => ['required', 'in:Создан,Принят,Готов к выдаче,Выполнен'],
        ]);

        // Нельзя вернуть заказ в статус "Создан" после того, как он был принят
        if ($request->status === 'Создан' && $order->status !== 'Создан') {
            return redirect()->route('manager.orders.index')
                ->with('error', 'Нельзя вернуть заказ в статус "Создан" после принятия.');
        }

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->route('manager.orders.index')
            ->with('success', 'Статус заказа успешно обновлен.');
    }
}










