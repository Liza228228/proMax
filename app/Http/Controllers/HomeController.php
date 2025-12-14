<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Показать главную страницу с новинками
     */
    public function index(): View
    {
        // Получаем ID товаров-новинок (установленные администратором)
        $featuredIds = Cache::get('featured_products', []);
        
        if (!empty($featuredIds)) {
            // Если администратор выбрал товары вручную
            $featuredProducts = Product::with(['images', 'category', 'stockProducts'])
                ->whereIn('id', $featuredIds)
                ->where('available', true)
                ->orderByRaw('FIELD(id, ' . implode(',', $featuredIds) . ')')
                ->take(6)
                ->get();
        } else {
            // Иначе показываем 6 последних добавленных товаров
            $featuredProducts = Product::with(['images', 'category', 'stockProducts'])
                ->where('available', true)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        }
        
        return view('index', compact('featuredProducts'));
    }
}

