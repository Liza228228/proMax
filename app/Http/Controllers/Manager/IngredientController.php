<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Recept;
use App\Models\Unit;
use App\Models\UnitType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IngredientController extends Controller
{
    /**
     * Display a listing of the ingredients.
     */
    public function index(Request $request): View
    {
        $query = Ingredient::with('unit');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $ingredients = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('manager.ingredients.index', compact('ingredients'));
    }

    /**
     * Show the form for creating a new ingredient.
     */
    public function create(): View
    {
        $unitTypes = UnitType::orderBy('name')->get();

        return view('manager.ingredients.create', compact('unitTypes'));
    }

    /**
     * Store a newly created ingredient in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'unit_type_id' => ['required', 'exists:unit_types,id'],
            'expiration_days' => ['required', 'integer', 'min:1'],
        ], [
            'name.required' => 'Поле "Название" обязательно для заполнения.',
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 100 символов.',
            'description.string' => 'Поле "Описание" должно быть строкой.',
            'unit_type_id.required' => 'Поле "Тип единицы измерения" обязательно для заполнения.',
            'unit_type_id.exists' => 'Выбранный тип единицы измерения не существует.',
            'expiration_days.required' => 'Поле "Срок годности" обязательно для заполнения.',
            'expiration_days.integer' => 'Поле "Срок годности" должно быть целым числом.',
            'expiration_days.min' => 'Срок годности должен быть не менее 1 дня.',
        ]);

        Ingredient::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'unit_type_id' => $validated['unit_type_id'],
            'expiration_date' => $validated['expiration_days'],
        ]);

        return redirect()->route('manager.ingredients.index')
            ->with('success', 'Ингредиент успешно создан.');
    }

    /**
     * Show the form for editing the specified ingredient.
     */
    public function edit(Ingredient $ingredient): View
    {
        $unitTypes = UnitType::orderBy('name')->get();

        return view('manager.ingredients.edit', compact('ingredient', 'unitTypes'));
    }

    /**
     * Update the specified ingredient in storage.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'unit_type_id' => ['required', 'exists:unit_types,id'],
            'expiration_days' => ['required', 'integer', 'min:1'],
        ], [
            'name.required' => 'Поле "Название" обязательно для заполнения.',
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 100 символов.',
            'description.string' => 'Поле "Описание" должно быть строкой.',
            'unit_type_id.required' => 'Поле "Тип единицы измерения" обязательно для заполнения.',
            'unit_type_id.exists' => 'Выбранный тип единицы измерения не существует.',
            'expiration_days.required' => 'Поле "Срок годности" обязательно для заполнения.',
            'expiration_days.integer' => 'Поле "Срок годности" должно быть целым числом.',
            'expiration_days.min' => 'Срок годности должен быть не менее 1 дня.',
        ]);

        $ingredient->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'unit_type_id' => $validated['unit_type_id'],
            'expiration_date' => $validated['expiration_days'],
        ]);

        return redirect()->route('manager.ingredients.index')
            ->with('success', 'Ингредиент успешно обновлён.');
    }

    /**
     * Remove the specified ingredient from storage.
     */
    public function destroy(Ingredient $ingredient): RedirectResponse
    {
        // Проверяем, используется ли ингредиент в рецептах
        $receptsCount = Recept::where('idIngredient', $ingredient->id)->count();
        
        if ($receptsCount > 0) {
            $errorMessage = "Нельзя удалить ингредиент '{$ingredient->name}', так как он используется в {$receptsCount} " . 
                ($receptsCount === 1 ? 'рецепте' : 'рецептах') . '.';
            
            return redirect()->route('manager.ingredients.index')
                ->with('error', $errorMessage);
        }

        $ingredient->delete();

        return redirect()->route('manager.ingredients.index')
            ->with('success', 'Ингредиент успешно удалён.');
    }
}


