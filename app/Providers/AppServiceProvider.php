<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Отключаем проверку SSL-сертификата глобально для curl
        // (для локальной разработки или при проблемах с сертификатами)
        // ВНИМАНИЕ: В продакшене это не рекомендуется!
        if (function_exists('curl_setopt')) {
            // Устанавливаем глобальные опции curl через переопределение curl_exec
            // Это отключит проверку SSL для всех curl запросов
            if (!defined('CURLOPT_SSL_VERIFYPEER_DISABLED')) {
                // Используем глобальные настройки через ini_set
                ini_set('curl.cainfo', '');
            }
        }
    }
}
