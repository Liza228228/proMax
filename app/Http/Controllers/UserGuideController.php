<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class UserGuideController extends Controller
{
    /**
     * Генерация PDF руководства пользователя для гостя
     */
    public function guest()
    {
        $pdf = PDF::loadView('user-guide.guest-pdf');
        return $pdf->download('руководство-пользователя-гость.pdf');
    }

    /**
     * Генерация PDF руководства пользователя для обычного пользователя
     */
    public function user()
    {
        $pdf = PDF::loadView('user-guide.user-pdf');
        return $pdf->download('руководство-пользователя.pdf');
    }

    /**
     * Генерация PDF руководства пользователя для администратора
     */
    public function admin()
    {
        $pdf = PDF::loadView('user-guide.admin-pdf');
        return $pdf->download('руководство-администратора.pdf');
    }

    /**
     * Генерация PDF руководства пользователя для менеджера
     */
    public function manager()
    {
        $pdf = PDF::loadView('user-guide.manager-pdf');
        return $pdf->download('руководство-менеджера.pdf');
    }
}

