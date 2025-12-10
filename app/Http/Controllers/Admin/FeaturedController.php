<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FeaturedController extends Controller
{
    /**
     * Показать страницу управления новинками
     */
    public function index(): View
    {
        // Получаем выбранные товары
        $featuredIds = Cache::get('featured_products', []);
        
        // Получаем выбранные товары с сохранением порядка
        $featuredProducts = collect();
        if (!empty($featuredIds)) {
            $featuredProducts = Product::with(['category', 'images', 'stockProducts'])
                ->whereIn('id', $featuredIds)
                ->where('available', true)
                ->get()
                ->sortBy(function($product) use ($featuredIds) {
                    return array_search($product->id, $featuredIds);
                })
                ->values();
        }
        
        // Получаем все доступные товары с пагинацией, исключая уже выбранные
        $allProducts = Product::with(['category', 'images', 'stockProducts'])
            ->where('available', true)
            ->when(!empty($featuredIds), function($query) use ($featuredIds) {
                $query->whereNotIn('id', $featuredIds);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.featured.index', compact('featuredProducts', 'allProducts'));
    }
    
    /**
     * Добавить товар в новинки
     */
    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);
        
        $featuredIds = Cache::get('featured_products', []);
        
        if (count($featuredIds) >= 6) {
            return redirect()->back()->with('error', 'Можно добавить максимум 6 товаров в новинки');
        }
        
        if (!in_array($request->product_id, $featuredIds)) {
            $featuredIds[] = $request->product_id;
            Cache::forever('featured_products', $featuredIds);
        }
        
        return redirect()->back()->with('success', 'Товар добавлен в новинки');
    }
    
    /**
     * Удалить товар из новинок
     */
    public function remove(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);
        
        $featuredIds = Cache::get('featured_products', []);
        $featuredIds = array_values(array_diff($featuredIds, [$request->product_id]));
        
        Cache::forever('featured_products', $featuredIds);
        
        return redirect()->back()->with('success', 'Товар удален из новинок');
    }
    
    /**
     * Изменить порядок товаров
     */
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|exists:products,id'
        ]);
        
        Cache::forever('featured_products', $request->order);
        
        return redirect()->back()->with('success', 'Порядок товаров обновлен');
    }
    
    /**
     * Сбросить выбор (будут показываться последние 6 товаров)
     */
    public function reset(): RedirectResponse
    {
        Cache::forget('featured_products');
        
        return redirect()->back()->with('success', 'Настройки сброшены. Теперь показываются последние 6 добавленных товаров');
    }
}

