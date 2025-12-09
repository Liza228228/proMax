<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Показать страницу "О нас"
     */
    public function index(): View
    {
        return view('about');
    }
}

