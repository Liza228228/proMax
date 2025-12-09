<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request): View
    {
        $query = Category::query();

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name_category', 'like', "%{$search}%");
        }

        // Фильтр по доступности
        if ($request->filled('available')) {
            $query->where('available', $request->available === '1');
        }

        $categories = $query->withCount('products')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name_category' => ['required', 'string', 'max:100', 'unique:categories,name_category'],
            'available' => ['boolean'],
        ], [
            'name_category.required' => 'Поле "Название категории" обязательно для заполнения.',
            'name_category.string' => 'Поле "Название категории" должно быть строкой.',
            'name_category.max' => 'Поле "Название категории" не должно превышать 100 символов.',
            'name_category.unique' => 'Категория с таким названием уже существует.',
        ]);

        Category::create([
            'name_category' => $request->name_category,
            'available' => $request->has('available') ? 1 : 0,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно создана.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name_category' => ['required', 'string', 'max:100', 'unique:categories,name_category,'.$category->id],
            'available' => ['boolean'],
        ], [
            'name_category.required' => 'Поле "Название категории" обязательно для заполнения.',
            'name_category.string' => 'Поле "Название категории" должно быть строкой.',
            'name_category.max' => 'Поле "Название категории" не должно превышать 100 символов.',
            'name_category.unique' => 'Категория с таким названием уже существует.',
        ]);

        $category->update([
            'name_category' => $request->name_category,
            'available' => $request->has('available') ? 1 : 0,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно обновлена.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Проверка: нельзя удалить категорию, если в ней есть продукты
        if ($category->products()->exists()) {
            $productsCount = $category->products()->count();
            return redirect()->route('admin.categories.index')
                ->with('error', "Невозможно удалить категорию. В категории \"{$category->name_category}\" находится {$productsCount} " . $this->getProductsWord($productsCount) . ".");
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно удалена.');
    }

    /**
     * Получить правильное склонение слова "продукт"
     */
    private function getProductsWord(int $count): string
    {
        $lastDigit = $count % 10;
        $lastTwoDigits = $count % 100;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
            return 'продуктов';
        }

        if ($lastDigit == 1) {
            return 'продукт';
        } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
            return 'продукта';
        } else {
            return 'продуктов';
        }
    }
}










