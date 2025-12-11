<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Ingredient;
use App\Models\Recept;
use App\Models\StockIngredient;
use App\Models\StockMovement;
use App\Models\StockProduct;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request): View
    {
        // Обновляем доступность из stocks_products для всех продуктов
        Product::chunk(100, function($products) {
            foreach ($products as $product) {
                $totalQuantity = $product->stockProducts()
                    ->where('expiration_date', '>=', Carbon::today())
                    ->sum('quantity') ?? 0;
                
                $product->available = $totalQuantity > 0;
                $product->saveQuietly();
            }
        });

        $query = Product::with(['category', 'images', 'stockProducts']);

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_product', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Фильтр по категории
        if ($request->filled('category_id')) {
            $query->where('idCategory', $request->category_id);
        }

        // Фильтр по доступности
        if ($request->filled('available')) {
            $query->where('available', $request->available === '1');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $categories = Category::where('available', true)->orderBy('name_category')->get();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::where('available', true)->orderBy('name_category')->get();
        $ingredients = Ingredient::with('unitType')->orderBy('name')->get();
        $units = Unit::with('unitType')->orderBy('name')->get();
        
        // Подготавливаем данные для JavaScript
        $unitsData = $units->map(function($unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->name,
                'code' => $unit->code,
                'unit_type_id' => $unit->unit_type_id,
            ];
        })->values();
        
        $ingredientsData = $ingredients->map(function($ingredient) {
            return [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'unit_type_id' => $ingredient->unit_type_id,
            ];
        })->values();
        
        return view('admin.products.create', compact('categories', 'ingredients', 'units', 'unitsData', 'ingredientsData'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name_product' => ['required', 'string', 'max:100', 'unique:products,name_product'],
            'description' => ['nullable', 'string'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'price' => ['required', 'numeric', 'min:0'],
            'expiration_days' => ['required', 'integer', 'min:1'],
            'idCategory' => ['required', 'exists:categories,id'],
            'available' => ['boolean'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*.id' => ['required', 'exists:ingredients,id'],
            'ingredients.*.quantity' => ['required', 'numeric', 'min:0.001'],
            'ingredients.*.unit_id' => ['required', 'exists:units,id'],
        ], [
            'name_product.required' => 'Поле "Название товара" обязательно для заполнения.',
            'name_product.string' => 'Поле "Название товара" должно быть строкой.',
            'name_product.max' => 'Поле "Название товара" не должно превышать 100 символов.',
            'name_product.unique' => 'Продукт с таким названием уже существует.',
            'description.string' => 'Поле "Описание" должно быть строкой.',
            'weight.numeric' => 'Поле "Вес" должно быть числом.',
            'weight.min' => 'Вес не может быть отрицательным.',
            'weight.max' => 'Вес не должен превышать 999.99.',
            'price.required' => 'Поле "Цена" обязательно для заполнения.',
            'price.numeric' => 'Поле "Цена" должно быть числом.',
            'price.min' => 'Цена не может быть отрицательной.',
            'expiration_days.required' => 'Поле "Срок годности" обязательно для заполнения.',
            'expiration_days.integer' => 'Срок годности должен быть целым числом.',
            'expiration_days.min' => 'Срок годности должен быть не менее 1 дня.',
            'idCategory.required' => 'Поле "Категория" обязательно для заполнения.',
            'idCategory.exists' => 'Выбранная категория не существует.',
            'images.required' => 'Необходимо загрузить хотя бы одно изображение.',
            'images.array' => 'Изображения должны быть в виде массива.',
            'images.min' => 'Необходимо загрузить хотя бы одно изображение.',
            'images.*.image' => 'Файл должен быть изображением.',
            'images.*.mimes' => 'Изображение должно быть в формате: jpeg, png, jpg или gif.',
            'images.*.max' => 'Размер изображения не должен превышать 2 МБ.',
            'ingredients.required' => 'Необходимо добавить хотя бы один ингредиент для рецепта.',
            'ingredients.array' => 'Ингредиенты должны быть в виде массива.',
            'ingredients.min' => 'Необходимо добавить хотя бы один ингредиент для рецепта.',
            'ingredients.*.id.required' => 'Необходимо указать ID ингредиента.',
            'ingredients.*.id.exists' => 'Выбранный ингредиент не существует.',
            'ingredients.*.quantity.required' => 'Необходимо указать количество ингредиента.',
            'ingredients.*.quantity.numeric' => 'Количество ингредиента должно быть числом.',
            'ingredients.*.quantity.min' => 'Количество ингредиента должно быть больше нуля.',
            'ingredients.*.unit_id.required' => 'Необходимо выбрать единицу измерения для ингредиента.',
            'ingredients.*.unit_id.exists' => 'Выбранная единица измерения не существует.',
        ]);

        $product = Product::create([
            'name_product' => $request->name_product,
            'description' => $request->description,
            'weight' => $request->weight,
            'price' => $request->price,
            'expiration_date' => $request->expiration_days, // Сохраняем количество дней
            'idCategory' => $request->idCategory,
            'available' => $request->has('available') ? 1 : 0,
        ]);

        // Загрузка изображений
        if ($request->hasFile('images')) {
            $productFolder = 'image/product/' . $product->id;
            
            // Создаем папку для продукта, если её нет
            if (!file_exists(public_path($productFolder))) {
                mkdir(public_path($productFolder), 0755, true);
            }
            
            $primaryIndex = (int)($request->input('primary_image_index', 0));
            
            foreach ($request->file('images') as $index => $image) {
                $filename = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($productFolder), $filename);
                $path = $productFolder . '/' . $filename;
                
                ProductImage::create([
                    'path' => $path,
                    'is_primary' => $index === $primaryIndex ? 1 : 0,
                    'idProduct' => $product->id,
                ]);
            }
        }

        // Сохранение ингредиентов (рецепта)
        if ($request->filled('ingredients')) {
            foreach ($request->ingredients as $ingredientData) {
                if (!empty($ingredientData['id']) && !empty($ingredientData['quantity']) && !empty($ingredientData['unit_id'])) {
                    $unit = Unit::findOrFail($ingredientData['unit_id']);
                    $quantityInUserUnit = $ingredientData['quantity'];
                    
                    // Конвертируем количество в базовые единицы
                    $quantityInBaseUnits = round($quantityInUserUnit * $unit->multiplier_to_base);
                    
                    Recept::create([
                        'idProduct' => $product->id,
                        'idIngredient' => $ingredientData['id'],
                        'quantity' => $quantityInBaseUnits,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Продукт успешно создан.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        // Удаляем партии с количеством 0
        StockProduct::where('id_product', $product->id)
            ->where('quantity', '<=', 0)
            ->delete();
        
        $product->load(['category', 'images', 'recepts.ingredient.unitType', 'stockProducts']);
        $units = Unit::with('unitType')->orderBy('name')->get();
        
        // Подготавливаем данные рецепта с удобными единицами для отображения
        $recepts = $product->recepts->map(function($recept) use ($units) {
            $quantityBase = $recept->quantity; // количество в базовых единицах
            $ingredientUnitType = $recept->ingredient->unitType->name ?? 'Масса';
            
            // Автоматически выбираем удобную единицу
            $displayUnit = $this->getBestDisplayUnit($quantityBase, $units, $ingredientUnitType);
            $multiplier = $displayUnit->multiplier_to_base ?? 1;
            $displayQuantity = $multiplier > 0 ? $quantityBase / $multiplier : $quantityBase;
            
            $recept->display_quantity = round($displayQuantity, 3);
            $recept->display_unit = $displayUnit;
            
            return $recept;
        });
        
        return view('admin.products.show', compact('product', 'recepts'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $product->load(['images', 'recepts.ingredient.unitType']);
        $categories = Category::where('available', true)->orderBy('name_category')->get();
        $ingredients = Ingredient::with('unitType')->orderBy('name')->get();
        $units = Unit::with('unitType')->orderBy('name')->get();
        
        // Подготавливаем данные для JavaScript
        $unitsData = $units->map(function($unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->name,
                'code' => $unit->code,
                'unit_type_id' => $unit->unit_type_id,
            ];
        })->values();
        
        $ingredientsData = $ingredients->map(function($ingredient) {
            return [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'unit_type_id' => $ingredient->unit_type_id,
            ];
        })->values();
        
        // Подготавливаем данные рецепта с удобными единицами для отображения
        $receptsData = [];
        foreach ($product->recepts as $recept) {
            $quantityBase = $recept->quantity;
            $ingredientUnitType = $recept->ingredient->unitType->name ?? 'Масса';
            $displayUnit = $this->getBestDisplayUnit($quantityBase, $units, $ingredientUnitType);
            $multiplier = $displayUnit->multiplier_to_base ?? 1;
            $displayQuantity = $multiplier > 0 ? $quantityBase / $multiplier : $quantityBase;
            
            $receptsData[] = [
                'id' => $recept->id,
                'ingredient_id' => $recept->ingredient->id,
                'quantity' => round($displayQuantity, 3),
                'unit_id' => $displayUnit->id,
            ];
        }
        
        return view('admin.products.edit', compact('product', 'categories', 'ingredients', 'units', 'unitsData', 'ingredientsData', 'receptsData'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'name_product' => ['required', 'string', 'max:100', 'unique:products,name_product,' . $product->id],
            'description' => ['nullable', 'string'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'price' => ['required', 'numeric', 'min:0'],
            'expiration_days' => ['required', 'integer', 'min:1'],
            'idCategory' => ['required', 'exists:categories,id'],
            'available' => ['boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:product_images,id'],
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*.id' => ['required', 'exists:ingredients,id'],
            'ingredients.*.quantity' => ['required', 'numeric', 'min:0.001'],
            'ingredients.*.unit_id' => ['required', 'exists:units,id'],
        ], [
            'name_product.required' => 'Поле "Название товара" обязательно для заполнения.',
            'name_product.string' => 'Поле "Название товара" должно быть строкой.',
            'name_product.max' => 'Поле "Название товара" не должно превышать 100 символов.',
            'name_product.unique' => 'Продукт с таким названием уже существует.',
            'description.string' => 'Поле "Описание" должно быть строкой.',
            'weight.numeric' => 'Поле "Вес" должно быть числом.',
            'weight.min' => 'Вес не может быть отрицательным.',
            'weight.max' => 'Вес не должен превышать 999.99.',
            'price.required' => 'Поле "Цена" обязательно для заполнения.',
            'price.numeric' => 'Поле "Цена" должно быть числом.',
            'price.min' => 'Цена не может быть отрицательной.',
            'expiration_days.required' => 'Поле "Срок годности" обязательно для заполнения.',
            'expiration_days.integer' => 'Срок годности должен быть целым числом.',
            'expiration_days.min' => 'Срок годности должен быть не менее 1 дня.',
            'idCategory.required' => 'Поле "Категория" обязательно для заполнения.',
            'idCategory.exists' => 'Выбранная категория не существует.',
            'images.array' => 'Изображения должны быть в виде массива.',
            'images.*.image' => 'Файл должен быть изображением.',
            'images.*.mimes' => 'Изображение должно быть в формате: jpeg, png, jpg или gif.',
            'images.*.max' => 'Размер изображения не должен превышать 2 МБ.',
            'delete_images.array' => 'Изображения для удаления должны быть в виде массива.',
            'delete_images.*.integer' => 'ID изображения должно быть целым числом.',
            'delete_images.*.exists' => 'Выбранное изображение не существует.',
            'ingredients.required' => 'Необходимо добавить хотя бы один ингредиент для рецепта.',
            'ingredients.array' => 'Ингредиенты должны быть в виде массива.',
            'ingredients.min' => 'Необходимо добавить хотя бы один ингредиент для рецепта.',
            'ingredients.*.id.required' => 'Необходимо указать ID ингредиента.',
            'ingredients.*.id.exists' => 'Выбранный ингредиент не существует.',
            'ingredients.*.quantity.required' => 'Необходимо указать количество ингредиента.',
            'ingredients.*.quantity.numeric' => 'Количество ингредиента должно быть числом.',
            'ingredients.*.quantity.min' => 'Количество ингредиента должно быть больше нуля.',
            'ingredients.*.unit_id.required' => 'Необходимо выбрать единицу измерения для ингредиента.',
            'ingredients.*.unit_id.exists' => 'Выбранная единица измерения не существует.',
        ]);

        // Определяем доступность продукта
        $available = $request->has('available') ? 1 : 0;
        
        // Обновляем доступность на основе количества из stocks_products
        $totalQuantity = $product->stockProducts()
            ->where('expiration_date', '>=', Carbon::today())
            ->sum('quantity') ?? 0;
        
        if ($totalQuantity == 0) {
            $available = 0;
        }

        $product->update([
            'name_product' => $request->name_product,
            'description' => $request->description,
            'weight' => $request->weight,
            'price' => $request->price,
            'expiration_date' => $request->expiration_days, // Сохраняем количество дней
            'idCategory' => $request->idCategory,
            'available' => $available,
        ]);

        // Обновляем доступность из stocks_products
        $totalQuantity = $product->stockProducts()
            ->where('expiration_date', '>=', Carbon::today())
            ->sum('quantity');
        
        $product->available = $totalQuantity > 0;
        $product->save();

        // Удаление выбранных изображений
        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image) {
                    // Удаляем файл из public
                    $filePath = public_path($image->path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $image->delete();
                }
            }
        }

        // Загрузка новых изображений
        if ($request->hasFile('images')) {
            $productFolder = 'image/product/' . $product->id;
            
            // Создаем папку для продукта, если её нет
            if (!file_exists(public_path($productFolder))) {
                mkdir(public_path($productFolder), 0755, true);
            }
            
            $existingImagesCount = $product->images()->count();
            $hasPrimary = $product->images()->where('is_primary', 1)->count() > 0;
            $newPrimaryIndex = $request->filled('new_primary_image_index') ? (int)$request->input('new_primary_image_index') : null;
            
            foreach ($request->file('images') as $index => $image) {
                $filename = time() . '_' . ($existingImagesCount + $index) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($productFolder), $filename);
                $path = $productFolder . '/' . $filename;
                
                // Если нет главного фото среди существующих и это первое новое фото, или выбрано как главное
                $isPrimary = (!$hasPrimary && $index === 0) || ($newPrimaryIndex !== null && $index === $newPrimaryIndex);
                
                ProductImage::create([
                    'path' => $path,
                    'is_primary' => $isPrimary ? 1 : 0,
                    'idProduct' => $product->id,
                ]);
            }
        }

        // Установка основного изображения
        if ($request->filled('primary_image')) {
            ProductImage::where('idProduct', $product->id)->update(['is_primary' => 0]);
            ProductImage::where('id', $request->primary_image)
                ->where('idProduct', $product->id)
                ->update(['is_primary' => 1]);
        }

        // Обновление рецепта (ингредиентов)
        if ($request->filled('ingredients')) {
            // Удаляем старый рецепт
            Recept::where('idProduct', $product->id)->delete();
            
            // Создаем новый рецепт
            foreach ($request->ingredients as $ingredientData) {
                if (!empty($ingredientData['id']) && !empty($ingredientData['quantity']) && !empty($ingredientData['unit_id'])) {
                    $unit = Unit::findOrFail($ingredientData['unit_id']);
                    $quantityInUserUnit = $ingredientData['quantity'];
                    
                    // Конвертируем количество в базовые единицы
                    $quantityInBaseUnits = round($quantityInUserUnit * $unit->multiplier_to_base);
                    
                    Recept::create([
                        'idProduct' => $product->id,
                        'idIngredient' => $ingredientData['id'],
                        'quantity' => $quantityInBaseUnits,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Продукт успешно обновлен.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Удаление изображений
        foreach ($product->images as $image) {
            $filePath = public_path($image->path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Удаление папки продукта, если она пуста
        $productFolder = public_path('image/product/' . $product->id);
        if (is_dir($productFolder)) {
            // Удаляем все файлы в папке
            $files = glob($productFolder . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            // Удаляем саму папку
            rmdir($productFolder);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Продукт успешно удален.');
    }

    /**
     * Списать ингредиенты со склада при увеличении количества продукции
     */
    private function deductIngredientsFromStock(Product $product, int $quantityToProduce): void
    {
        // Получаем все рецепты продукта
        $recepts = Recept::where('idProduct', $product->id)->get();

        if ($recepts->isEmpty()) {
            return; // Нет рецепта, нечего списывать
        }

        // Получаем первый склад (кондитерская) - склад с минимальным ID
        $mainWarehouse = Warehouse::orderBy('id', 'asc')->first();
        
        if (!$mainWarehouse) {
            throw new \Exception('Не найден основной склад (кондитерская).');
        }

        DB::beginTransaction();
        try {
            foreach ($recepts as $recept) {
                $ingredientId = $recept->idIngredient;
                $ingredientQuantityPerProduct = $recept->quantity;
                $totalNeeded = $ingredientQuantityPerProduct * $quantityToProduce;

                // Получаем все актуальные партии ингредиента (не просроченные), отсортированные по сроку годности (FIFO)
                // Сначала партии с ближайшим сроком годности, затем партии без срока годности
                $availableStocks = StockIngredient::where('idIngredient', $ingredientId)
                    ->where('idWarehouse', $mainWarehouse->id)
                    ->where(function($query) {
                        $query->where('expiration_date', '>=', Carbon::today())
                              ->orWhereNull('expiration_date');
                    })
                    ->orderByRaw('CASE WHEN expiration_date IS NULL THEN 1 ELSE 0 END ASC') // Сначала партии с датой
                    ->orderBy('expiration_date', 'asc') // Затем по ближайшему сроку годности
                    ->orderBy('created_at', 'asc') // Если срок одинаковый, то по дате создания
                    ->get();

                // Вычисляем общее доступное количество из актуальных партий
                $totalAvailable = $availableStocks->sum('quantity');

                if ($totalAvailable < $totalNeeded) {
                    $ingredient = Ingredient::find($ingredientId);
                    throw new \Exception("Недостаточно ингредиента '{$ingredient->name}' на складе '{$mainWarehouse->name}'. Требуется: {$totalNeeded}, доступно: {$totalAvailable}");
                }

                // Списываем ингредиент с основного склада по принципу FIFO (сначала ближайший срок годности)
                $remainingToDeduct = $totalNeeded;
                
                foreach ($availableStocks as $stock) {
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

                // Создаем запись в истории перемещений (списание на производство)
                StockMovement::create([
                    'from_warehouse_id' => $mainWarehouse->id,
                    'to_warehouse_id' => null, // null означает списание в производство
                    'ingredient_id' => $ingredientId,
                    'quantity' => $totalNeeded,
                    'product_id' => $product->id,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Show the form for adding quantity to product.
     */
    public function showAddQuantity(Product $product): View
    {
        $product->load(['recepts.ingredient.unitType']);
        $units = Unit::with('unitType')->orderBy('name')->get();
        
        // Получаем основной склад (кондитерская) - склад с минимальным ID
        $mainWarehouse = Warehouse::orderBy('id', 'asc')->first();
        
        // Подготавливаем данные рецепта с удобными единицами и количеством на складе
        $recepts = $product->recepts->map(function($recept) use ($units, $mainWarehouse) {
            $quantityBase = $recept->quantity; // количество в базовых единицах
            $ingredientUnitType = $recept->ingredient->unitType->name ?? 'Масса';
            
            // Автоматически выбираем удобную единицу
            $displayUnit = $this->getBestDisplayUnit($quantityBase, $units, $ingredientUnitType);
            $multiplier = $displayUnit->multiplier_to_base ?? 1;
            $displayQuantity = $multiplier > 0 ? $quantityBase / $multiplier : $quantityBase;
            
            $recept->display_quantity = round($displayQuantity, 3);
            $recept->display_unit = $displayUnit;
            
            // Получаем количество ингредиента на основном складе из всех актуальных партий (не просроченных)
            $stockQuantityBase = StockIngredient::where('idIngredient', $recept->ingredient->id)
                ->where('idWarehouse', $mainWarehouse->id ?? 0)
                ->where(function($query) {
                    $query->where('expiration_date', '>=', Carbon::today())
                          ->orWhereNull('expiration_date');
                })
                ->sum('quantity') ?? 0;
            
            // Конвертируем количество на складе в удобную единицу
            $stockDisplayQuantity = $multiplier > 0 ? $stockQuantityBase / $multiplier : $stockQuantityBase;
            
            $recept->stock_quantity_base = $stockQuantityBase;
            $recept->stock_display_quantity = round($stockDisplayQuantity, 3);
            
            return $recept;
        });
        
        return view('admin.products.add-quantity', compact('product', 'recepts', 'units', 'mainWarehouse'));
    }

    /**
     * Add quantity to product and deduct ingredients from stock.
     */
    public function addQuantity(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ], [
            'quantity.required' => 'Необходимо указать количество.',
            'quantity.integer' => 'Количество должно быть целым числом.',
            'quantity.min' => 'Количество должно быть не менее 1.',
        ]);

        $quantityToAdd = $request->quantity;

        // Проверяем наличие ингредиентов на складах
        try {
            $this->deductIngredientsFromStock($product, $quantityToAdd);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['quantity' => $e->getMessage()]);
        }

        // Вычисляем срок годности: текущая дата + количество дней из products.expiration_date
        // expiration_date в products хранит количество дней, а не дату
        $expirationDays = $product->expiration_date ?? 0;
        $expirationDate = Carbon::today()->addDays($expirationDays);

        // Проверяем, есть ли уже партия с такой же датой срока годности
        $existingStock = StockProduct::where('id_product', $product->id)
            ->whereDate('expiration_date', $expirationDate->toDateString())
            ->first();

        if ($existingStock) {
            // Если партия существует, суммируем количество
            $existingStock->quantity += $quantityToAdd;
            $existingStock->save();
        } else {
            // Если партии нет, создаем новую запись
            StockProduct::create([
                'id_product' => $product->id,
                'quantity' => $quantityToAdd,
                'expiration_date' => $expirationDate,
            ]);
        }

        // Обновляем доступность из stocks_products
        $totalQuantity = $product->stockProducts()
            ->where('expiration_date', '>=', Carbon::today())
            ->sum('quantity');
        
        // Если продукт был недоступен и теперь есть количество, делаем его доступным
        if (!$product->available && $totalQuantity > 0) {
            $product->available = true;
        } elseif ($totalQuantity == 0) {
            $product->available = false;
        }
        
        $product->save();

        return redirect()->route('admin.products.show', $product)
            ->with('success', "Успешно добавлено {$quantityToAdd} шт. продукта. Ингредиенты списаны со складов.");
    }

    /**
     * Автоматически выбрать удобную единицу для отображения
     */
    private function getBestDisplayUnit(int $quantityBase, $allUnits, string $ingredientUnitType = 'Масса'): Unit
    {
        // Получаем все единицы того же типа, что и ингредиент
        $unitsOfSameType = $allUnits->filter(function ($unit) use ($ingredientUnitType) {
            return $unit->unitType && $unit->unitType->name === $ingredientUnitType;
        });
        
        if ($unitsOfSameType->isEmpty()) {
            return $allUnits->first();
        }
        
        // Находим базовую единицу нужного типа
        $baseUnit = $unitsOfSameType->where('is_base', true)->first();
        if (!$baseUnit) {
            $baseUnit = $unitsOfSameType->where('multiplier_to_base', 1)->first();
        }
        
        if (!$baseUnit) {
            return $unitsOfSameType->first();
        }
        
        // Для массы и объёма: если количество >= 1000 базовых единиц, используем большую единицу
        if (in_array($ingredientUnitType, ['Масса', 'Объём']) && $quantityBase >= 1000) {
            $largerUnit = $unitsOfSameType->where('multiplier_to_base', '>=', 1000)
                ->sortBy('multiplier_to_base')
                ->first();
            if ($largerUnit) {
                return $largerUnit;
            }
        }
        
        return $baseUnit;
    }

    /**
     * Check if product name already exists (for AJAX validation).
     */
    public function checkName(Request $request)
    {
        $name = $request->input('name_product');
        $productId = $request->input('product_id'); // For edit form
        
        if (empty($name)) {
            return response()->json(['exists' => false]);
        }

        $query = Product::where('name_product', $name);
        
        if ($productId) {
            $query->where('id', '!=', $productId);
        }
        
        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }
}

