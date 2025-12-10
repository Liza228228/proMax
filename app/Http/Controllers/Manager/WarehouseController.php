<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Ingredient;
use App\Models\StockIngredient;
use App\Models\StockMovement;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the warehouses.
     */
    public function index(Request $request): View
    {
        $query = Warehouse::withCount('stockIngredients');

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('street', 'like', "%{$search}%");
            });
        }

        $warehouses = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('manager.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new warehouse.
     */
    public function create(): View
    {
        return view('manager.warehouses.create');
    }

    /**
     * Store a newly created warehouse in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:100'],
            'house' => ['required', 'string', 'max:100'],
        ]);

        Warehouse::create([
            'name' => $request->name,
            'city' => $request->city,
            'street' => $request->street,
            'house' => $request->house,
        ]);

        return redirect()->route('manager.warehouses.index')
            ->with('success', 'Склад успешно создан.');
    }

    /**
     * Display the specified warehouse.
     */
    public function show(Request $request, Warehouse $warehouse): View
    {
        $ingredients = Ingredient::with('unitType')->orderBy('name')->get();
        $units = Unit::with('unitType')->orderBy('name')->get();
        
        // Создаем запрос для ингредиентов на складе с пагинацией
        $query = StockIngredient::where('idWarehouse', $warehouse->id)
            ->with(['ingredient.unitType']);
        
        // Фильтрация ингредиентов на складе по поисковому запросу
        if ($request->filled('search_ingredient')) {
            $search = trim($request->search_ingredient);
            $query->whereHas('ingredient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        // Фильтрация по сроку годности (актуальное/просроченное/все)
        $expirationFilter = $request->input('expiration_filter', 'all');
        
        if ($expirationFilter === 'actual') {
            // Актуальные: срок годности >= сегодня (не NULL и не просрочено)
            $query->where('expiration_date', '>=', Carbon::today());
        } elseif ($expirationFilter === 'expired') {
            // Просроченные: срок годности < сегодня (только те, у которых есть дата и она истекла)
            $query->where('expiration_date', '<', Carbon::today())
                  ->whereNotNull('expiration_date');
        }
        // Если 'all' или не передан - показываем все ингредиенты без фильтрации по сроку годности
        
        // Обычная пагинация для всех записей
        $stockIngredients = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        
        // Автоматически определяем удобную единицу для каждого ингредиента
        $stockIngredients->getCollection()->transform(function ($stock) use ($units) {
            $quantityBase = $stock->quantity; // количество в базовых единицах
            
            // Получаем тип единицы из ингредиента через связь
            $ingredientUnitType = $stock->ingredient->unitType->name ?? 'Масса';
            
            // Автоматически выбираем удобную единицу на основе количества и типа ингредиента
            $displayUnit = $this->getBestDisplayUnit($quantityBase, $units, $ingredientUnitType);
            
            // Пересчитываем количество в выбранную единицу
            $multiplier = $displayUnit->multiplier_to_base ?? 1;
            $displayQuantity = $multiplier > 0 ? $quantityBase / $multiplier : $quantityBase;
            
            $stock->display_quantity = $displayQuantity;
            $stock->display_unit = $displayUnit;
            
            return $stock;
        });
        
        return view('manager.warehouses.show', compact('warehouse', 'ingredients', 'units', 'stockIngredients'));
    }
    
    /**
     * Получить единицы измерения определённого типа
     */
    private function getUnitsByType($units, string $typeName): \Illuminate\Support\Collection
    {
        // typeName - это name из unit_types (Масса, Объём, Штуки)
        return $units->filter(function ($unit) use ($typeName) {
            return $unit->unitType && $unit->unitType->name === $typeName;
        });
    }
    
    /**
     * Автоматически выбрать удобную единицу для отображения
     */
    private function getBestDisplayUnit(int $quantityBase, $allUnits, string $ingredientUnitType = 'Масса'): Unit
    {
        // Получаем все единицы того же типа, что и ингредиент
        $unitsOfSameType = $this->getUnitsByType($allUnits, $ingredientUnitType);
        
        if ($unitsOfSameType->isEmpty()) {
            // Если единиц нужного типа нет, используем первую доступную
            return $allUnits->first();
        }
        
        // Находим базовую единицу нужного типа (is_base = true или multiplier_to_base = 1)
        $baseUnit = $unitsOfSameType->where('is_base', true)->first();
        if (!$baseUnit) {
            $baseUnit = $unitsOfSameType->where('multiplier_to_base', 1)->first();
        }
        
        if (!$baseUnit) {
            return $unitsOfSameType->first();
        }
        
        // Для массы и объёма: если количество >= 1000 базовых единиц, используем большую единицу
        if (in_array($ingredientUnitType, ['Масса', 'Объём']) && $quantityBase >= 1000) {
            // Ищем единицу с multiplier >= 1000 (кг для массы, л для объёма)
            $largerUnit = $unitsOfSameType->where('multiplier_to_base', '>=', 1000)
                ->sortBy('multiplier_to_base')
                ->first();
            if ($largerUnit) {
                return $largerUnit;
            }
        }
        
        // Для штук или если количество < 1000, используем базовую единицу
        return $baseUnit;
    }

    /**
     * Show the form for editing the specified warehouse.
     */
    public function edit(Warehouse $warehouse): View
    {
        // Защита основного склада от редактирования
        if ($warehouse->is_main) {
            return redirect()->route('manager.warehouses.index')
                ->with('error', 'Нельзя редактировать основной склад "Кондитерская".');
        }

        return view('manager.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified warehouse in storage.
     */
    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        // Защита основного склада от изменения
        if ($warehouse->is_main) {
            return redirect()->route('manager.warehouses.index')
                ->with('error', 'Нельзя изменять основной склад "Кондитерская".');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:100'],
            'house' => ['required', 'string', 'max:100'],
        ]);

        $warehouse->update([
            'name' => $request->name,
            'city' => $request->city,
            'street' => $request->street,
            'house' => $request->house,
        ]);

        return redirect()->route('manager.warehouses.index')
            ->with('success', 'Склад успешно обновлен.');
    }

    /**
     * Remove the specified warehouse from storage.
     */
    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        // Защита основного склада от удаления
        if ($warehouse->is_main) {
            return redirect()->route('manager.warehouses.index')
                ->with('error', 'Нельзя удалить основной склад "Кондитерская".');
        }

        // Проверка на наличие ингредиентов на складе
        if ($warehouse->stockIngredients()->exists()) {
            return redirect()->route('manager.warehouses.index')
                ->with('error', 'Нельзя удалить склад, на котором есть ингредиенты.');
        }

        $warehouse->delete();

        return redirect()->route('manager.warehouses.index')
            ->with('success', 'Склад успешно удален.');
    }

    /**
     * Add ingredient to warehouse.
     */
    public function addIngredient(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $rules = [
            'idIngredient' => ['required', 'exists:ingredients,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'quantity' => ['required', 'numeric', 'min:0'],
        ];

        $request->validate($rules, [
            'idIngredient.required' => 'Необходимо выбрать ингредиент.',
            'idIngredient.exists' => 'Выбранный ингредиент недействителен.',
            'unit_id.required' => 'Необходимо выбрать единицу измерения.',
        ]);

        $ingredientId = $request->idIngredient;
        $unit = Unit::findOrFail($request->unit_id);
        $quantityUi = $request->quantity;

        // Конвертируем количество в базовые единицы (граммы)
        $quantityBase = round($quantityUi * $unit->multiplier_to_base);

        // Максимальное значение для integer в MySQL
        $maxInteger = 2147483647;
        
        // Проверяем, не превышает ли значение максимальное для integer
        if ($quantityBase > $maxInteger) {
            return redirect()->back()
                ->with('warning', 'Количество слишком большое для сохранения.')
                ->withInput();
        }

        // Получаем ингредиент для вычисления срока годности
        $ingredient = Ingredient::findOrFail($ingredientId);
        
        // Вычисляем срок годности: текущая дата + количество дней из ingredients.expiration_date
        $expirationDays = $ingredient->expiration_date ?? 0;
        $expirationDate = Carbon::today()->addDays($expirationDays);

        // Проверяем, есть ли уже партия с такой же датой срока годности на этом складе
        $existingStock = StockIngredient::where('idWarehouse', $warehouse->id)
            ->where('idIngredient', $ingredientId)
            ->whereDate('expiration_date', $expirationDate->toDateString())
            ->first();

        if ($existingStock) {
            // Если партия существует, суммируем количество
            $newQuantity = $existingStock->quantity + $quantityBase;
            if ($newQuantity > $maxInteger) {
                return redirect()->back()
                    ->with('warning', 'Количество слишком большое для сохранения.')
                    ->withInput();
            }
            
            // Обновляем количество и срок годности (если его не было)
            $updateData = ['quantity' => $newQuantity];
            if (!$existingStock->expiration_date) {
                $updateData['expiration_date'] = $expirationDate;
            }
            
            $existingStock->update($updateData);
        } else {
            // Если партии нет, создаем новую запись
            StockIngredient::create([
                'idWarehouse' => $warehouse->id,
                'idIngredient' => $ingredientId,
                'quantity' => $quantityBase,
                'expiration_date' => $expirationDate,
            ]);
        }

        // Создаем запись о начислении ингредиента на склад
        // from_warehouse_id = null означает, что это начисление, а не перемещение
        StockMovement::create([
            'from_warehouse_id' => null,
            'to_warehouse_id' => $warehouse->id,
            'ingredient_id' => $ingredientId,
            'quantity' => $quantityBase,
        ]);

        return redirect()->route('manager.warehouses.show', $warehouse)
            ->with('success', 'Ингредиент успешно добавлен на склад.');
    }

    /**
     * Show the form for transferring ingredients between warehouses.
     */
    public function showTransferForm(Warehouse $warehouse): View
    {
        $warehouses = Warehouse::where('id', '!=', $warehouse->id)->orderBy('name')->get();
        
        // Получаем только те ингредиенты, которые есть на текущем складе
        $warehouse->load(['stockIngredients.ingredient.unitType']);
        $ingredientIds = $warehouse->stockIngredients->pluck('idIngredient')->unique();
        $ingredients = Ingredient::with('unitType')
            ->whereIn('id', $ingredientIds)
            ->orderBy('name')
            ->get();
        
        $units = Unit::with('unitType')->orderBy('name')->get();

        // Подготавливаем данные о партиях ингредиентов с их сроками годности для JavaScript
        // Учитываем только актуальные партии (не просроченные)
        $stockBatches = []; // Структура: [ingredientId][expiration_date] = quantity
        foreach ($warehouse->stockIngredients as $stock) {
            // Проверяем, что партия не просрочена
            if ($stock->expiration_date && Carbon::parse($stock->expiration_date)->isPast()) {
                continue; // Пропускаем просроченные партии
            }
            
            $ingredientId = $stock->idIngredient;
            // Используем строковый ключ для null, чтобы избежать проблем с JSON сериализацией
            $expirationDateKey = $stock->expiration_date ? Carbon::parse($stock->expiration_date)->format('Y-m-d') : '__null__';
            
            if (!isset($stockBatches[$ingredientId])) {
                $stockBatches[$ingredientId] = [];
            }
            
            if (!isset($stockBatches[$ingredientId][$expirationDateKey])) {
                $stockBatches[$ingredientId][$expirationDateKey] = [
                    'quantity' => 0,
                    'expiration_date' => $stock->expiration_date ? Carbon::parse($stock->expiration_date)->format('Y-m-d') : null,
                    'expiration_date_display' => $stock->expiration_date ? Carbon::parse($stock->expiration_date)->format('d.m.Y') : 'Без даты'
                ];
            }
            
            $stockBatches[$ingredientId][$expirationDateKey]['quantity'] += $stock->quantity; // суммируем количество в базовых единицах
        }

        // Подготавливаем данные о единицах измерения для JavaScript
        $unitsData = [];
        foreach ($units as $unit) {
            $unitsData[$unit->id] = [
                'multiplier_to_base' => $unit->multiplier_to_base,
                'unit_type_id' => $unit->unitType->id ?? null
            ];
        }

        return view('manager.warehouses.transfer', compact('warehouse', 'warehouses', 'ingredients', 'units', 'stockBatches', 'unitsData'));
    }

    /**
     * Transfer ingredient between warehouses.
     */
    public function transfer(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $validated = $request->validate([
            'ingredient_id' => ['required', 'exists:ingredients,id'],
            'expiration_date' => ['required'], // Поле обязательно, но может быть пустой строкой для партий без даты
            'unit_id' => ['required', 'exists:units,id'],
            'quantity' => ['required', 'numeric', 'min:0.001'],
            'to_warehouse_id' => ['required', 'exists:warehouses,id', 'different:from_warehouse_id'],
        ], [
            'ingredient_id.required' => 'Необходимо выбрать ингредиент.',
            'expiration_date.required' => 'Необходимо выбрать срок годности.',
            'unit_id.required' => 'Необходимо выбрать единицу измерения.',
            'quantity.required' => 'Необходимо указать количество.',
            'quantity.min' => 'Количество должно быть больше нуля.',
            'to_warehouse_id.required' => 'Необходимо выбрать склад-получатель.',
            'to_warehouse_id.different' => 'Склад-получатель должен отличаться от склада-источника.',
        ]);

        $ingredientId = $validated['ingredient_id'];
        // Обрабатываем пустую строку как null для партий без даты
        $requestedExpirationDate = empty($validated['expiration_date']) ? null : $validated['expiration_date'];
        $unit = Unit::findOrFail($validated['unit_id']);
        $quantityUi = $validated['quantity'];
        $toWarehouseId = $validated['to_warehouse_id'];

        // Конвертируем количество в базовые единицы
        $quantityBase = round($quantityUi * $unit->multiplier_to_base);

        // Максимальное значение для integer в MySQL
        $maxInteger = 2147483647;
        
        // Проверяем, не превышает ли значение максимальное для integer
        if ($quantityBase > $maxInteger) {
            return redirect()->back()
                ->with('warning', 'Количество слишком большое для сохранения. Максимальное значение: ' . number_format($maxInteger, 0, '.', ' ') . '. Пожалуйста, уменьшите количество или используйте другую единицу измерения.')
                ->withInput();
        }

        // Проверяем наличие выбранной партии на складе-источнике
        $query = StockIngredient::where('idWarehouse', $warehouse->id)
            ->where('idIngredient', $ingredientId);
        
        // Фильтруем по выбранному сроку годности
        if ($requestedExpirationDate === null || $requestedExpirationDate === '') {
            $query->whereNull('expiration_date');
        } else {
            $query->whereDate('expiration_date', $requestedExpirationDate);
        }
        
        // Получаем все партии с выбранным сроком годности
        $selectedStocks = $query->orderBy('created_at', 'asc')->get();
        
        // Проверяем, что партии не просрочены
        $validStocks = $selectedStocks->filter(function($stock) {
            if (!$stock->expiration_date) {
                return true; // Партии без даты считаются валидными
            }
            return Carbon::parse($stock->expiration_date)->isFuture() || Carbon::parse($stock->expiration_date)->isToday();
        });
        
        if ($validStocks->isEmpty()) {
            return redirect()->back()
                ->withErrors(['expiration_date' => 'Выбранная партия просрочена или не существует.'])
                ->withInput();
        }
        
        // Вычисляем доступное количество в выбранной партии
        $totalAvailable = $validStocks->sum('quantity');
        
        if ($totalAvailable < $quantityBase) {
            return redirect()->back()
                ->withErrors([ 'Недостаточно ингредиента в выбранной партии. ' ])
                ->withInput();
        }

        // Списываем со склада-источника из выбранной партии (FIFO по created_at)
        $remainingToDeduct = $quantityBase;
        
        foreach ($validStocks as $stock) {
            if ($remainingToDeduct <= 0) {
                break;
            }
            
            $deductAmount = min($remainingToDeduct, $stock->quantity);
            $stock->quantity -= $deductAmount;
            
            if ($stock->quantity <= 0) {
                $stock->delete();
            } else {
                $stock->save();
            }
            
            $remainingToDeduct -= $deductAmount;
        }
        
        // Используем выбранный срок годности
        $expirationDate = $requestedExpirationDate ? Carbon::parse($requestedExpirationDate) : null;

        // Добавляем на склад-получатель
        // Проверяем, есть ли уже партия с такой же датой срока годности
        $toStockQuery = StockIngredient::where('idWarehouse', $toWarehouseId)
            ->where('idIngredient', $ingredientId);
        
        if ($expirationDate) {
            $toStockQuery->whereDate('expiration_date', $expirationDate->toDateString());
        } else {
            $toStockQuery->whereNull('expiration_date');
        }
        
        $toStock = $toStockQuery->first();

        if ($toStock) {
            // Если партия существует, суммируем количество
            $newQuantity = $toStock->quantity + $quantityBase;
            if ($newQuantity > $maxInteger) {
                return redirect()->back()
                    ->with('warning', 'Итоговое количество на складе-получателе слишком большое для сохранения. Максимальное значение: ' . number_format($maxInteger, 0, '.', ' ') . '. Текущее количество: ' . number_format($toStock->quantity, 0, '.', ' ') . '.')
                    ->withInput();
            }
            
            $toStock->quantity = $newQuantity;
            $toStock->save();
        } else {
            // Если партии нет, создаем новую с сохранением срока годности
            StockIngredient::create([
                'idWarehouse' => $toWarehouseId,
                'idIngredient' => $ingredientId,
                'quantity' => $quantityBase,
                'expiration_date' => $expirationDate,
            ]);
        }

        // Создаем запись о перемещении
        StockMovement::create([
            'from_warehouse_id' => $warehouse->id,
            'to_warehouse_id' => $toWarehouseId,
            'ingredient_id' => $ingredientId,
            'quantity' => $quantityBase,
        ]);

        $toWarehouse = Warehouse::findOrFail($toWarehouseId);

        return redirect()->route('manager.warehouses.show', $warehouse)
            ->with('success', "Ингредиент успешно перемещен на склад '{$toWarehouse->name}'.");
    }

    /**
     * Display movement history for a specific warehouse.
     */
    public function movementHistory(Request $request, Warehouse $warehouse): View
    {
        $query = StockMovement::with(['fromWarehouse', 'toWarehouse', 'ingredient.unitType', 'product'])
            ->where(function($q) use ($warehouse) {
                // Показываем все перемещения, где склад является источником или получателем
                $q->where('from_warehouse_id', $warehouse->id)
                  ->orWhere('to_warehouse_id', $warehouse->id);
            });

        // Фильтр по типу перемещения (входящие/исходящие/все)
        if ($request->filled('movement_type')) {
            $movementType = $request->movement_type;
            if ($movementType === 'incoming') {
                $query->where('to_warehouse_id', $warehouse->id);
            } elseif ($movementType === 'outgoing') {
                $query->where('from_warehouse_id', $warehouse->id);
            }
        }

        // Фильтр по начислениям и списаниям
        if ($request->filled('operation_type')) {
            $operationType = $request->operation_type;
            if ($operationType === 'accrual') {
                // Начисления (входящие без источника)
                $query->where('to_warehouse_id', $warehouse->id)
                      ->whereNull('from_warehouse_id');
            } elseif ($operationType === 'writeoff') {
                // Списания (исходящие без получателя)
                $query->where('from_warehouse_id', $warehouse->id)
                      ->whereNull('to_warehouse_id');
            }
        }

        // Фильтр по складу-источнику (для входящих перемещений)
        if ($request->filled('from_warehouse_id')) {
            $query->where('from_warehouse_id', $request->from_warehouse_id);
        }

        // Фильтр по складу-получателю (для исходящих перемещений)
        if ($request->filled('to_warehouse_id')) {
            $query->where('to_warehouse_id', $request->to_warehouse_id);
        }

        // Фильтр по ингредиенту
        if ($request->filled('ingredient_id')) {
            $query->where('ingredient_id', $request->ingredient_id);
        }

        // Поиск по названию ингредиента
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('ingredient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Фильтр по дате (от)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Фильтр по дате (до)
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        // Получаем данные для фильтров
        $warehouses = Warehouse::where('id', '!=', $warehouse->id)->orderBy('name')->get();
        $ingredients = Ingredient::with('unitType')->orderBy('name')->get();
        $units = Unit::with('unitType')->orderBy('name')->get();

        // Форматируем движения для отображения
        $movements->getCollection()->transform(function ($movement) use ($units) {
            $quantityBase = $movement->quantity;
            $ingredientUnitType = $movement->ingredient->unitType->name ?? 'Масса';
            
            // Автоматически выбираем удобную единицу
            $displayUnit = $this->getBestDisplayUnit($quantityBase, $units, $ingredientUnitType);
            $multiplier = $displayUnit->multiplier_to_base ?? 1;
            $displayQuantity = $multiplier > 0 ? $quantityBase / $multiplier : $quantityBase;
            
            $movement->display_quantity = round($displayQuantity, 3);
            $movement->display_unit = $displayUnit;
            
            return $movement;
        });

        return view('manager.warehouses.movement-history', compact('movements', 'warehouse', 'warehouses', 'ingredients'));
    }
}

