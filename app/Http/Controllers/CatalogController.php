<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class CatalogController extends Controller
{
    /**
     * Display the product catalog for regular users (categories with products).
     */
    public function index(Request $request): View
    {
        // Если есть поиск, показываем все товары с фильтрацией
        if ($request->filled('search') || $request->filled('category_id') || $request->filled('price_min') || $request->filled('price_max')) {
            $query = Product::with(['category', 'images', 'stockProducts'])
                ->where('available', true)
                ->whereHas('stockProducts', function($q) {
                    $q->where('expiration_date', '>=', Carbon::today())
                      ->where('quantity', '>', 0);
                });

            // Поиск по названию и описанию
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

            // Фильтр по цене
            if ($request->filled('price_min')) {
                $query->where('price', '>=', $request->price_min);
            }
            if ($request->filled('price_max')) {
                $query->where('price', '<=', $request->price_max);
            }

            // Сортировка по дате создания (по умолчанию)
            $query->orderBy('created_at', 'desc');

            $products = $query->paginate(15)->withQueryString();
            $categories = Category::where('available', true)->orderBy('name_category')->get();
            
            return view('catalog.index', compact('products', 'categories'));
        }

        // Обычный режим - показываем категории с товарами
        $categories = Category::where('available', true)
            ->with(['products' => function($query) {
                $query->where('available', true)
                      ->whereHas('stockProducts', function($q) {
                          $q->where('expiration_date', '>=', Carbon::today())
                            ->where('quantity', '>', 0);
                      })
                      ->with(['images', 'stockProducts'])
                      ->orderBy('created_at', 'desc')
                      ->limit(12);
            }])
            ->withCount(['products' => function($query) {
                $query->where('available', true)
                      ->whereHas('stockProducts', function($q) {
                          $q->where('expiration_date', '>=', Carbon::today())
                            ->where('quantity', '>', 0);
                      });
            }])
            ->having('products_count', '>', 0)
            ->orderBy('name_category')
            ->get();

        return view('catalog.index', compact('categories'));
    }

    /**
     * Display products of a specific category.
     */
    public function category(Request $request, Category $category): View
    {
        $query = Product::with(['category', 'images', 'stockProducts'])
            ->where('idCategory', $category->id)
            ->where('available', true)
            ->whereHas('stockProducts', function($q) {
                $q->where('expiration_date', '>=', Carbon::today())
                  ->where('quantity', '>', 0);
            });

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_product', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Фильтр по цене
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Сортировка по дате создания (по умолчанию)
        $query->orderBy('created_at', 'desc');

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::where('available', true)->orderBy('name_category')->get();
        
        return view('catalog.index', compact('products', 'category', 'categories'));
    }

    /**
     * Display the specified product.
     */
    public function show(Request $request, Product $product): View
    {
        // Проверяем доступность продукта
        if (!$product->available) {
            abort(404, 'Продукт недоступен');
        }

        $product->load(['category', 'images', 'recepts.ingredient.unitType', 'stockProducts']);

        $allImages = $product->images;
        $primaryImage = null;

        if ($request->filled('image')) {
            $primaryImage = $allImages->firstWhere('id', (int) $request->query('image'));
        }

        if (!$primaryImage) {
            $primaryImage = $allImages->where('is_primary', 1)->first() ?? $allImages->first();
        }
        
        // Получаем похожие продукты из той же категории
        $relatedProducts = Product::with(['category', 'images', 'stockProducts'])
            ->where('idCategory', $product->idCategory)
            ->where('id', '!=', $product->id)
            ->where('available', true)
            ->whereHas('stockProducts', function($q) {
                $q->where('expiration_date', '>=', Carbon::today())
                  ->where('quantity', '>', 0);
            })
            ->limit(4)
            ->get();
        
        return view('catalog.show', compact('product', 'relatedProducts', 'primaryImage'));
    }
}

