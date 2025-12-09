<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\FeaturedController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Manager\OrderController;
use App\Http\Controllers\Manager\WarehouseController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\UserGuideController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');

// Страница "О нас"
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Руководство пользователя
Route::prefix('user-guide')->name('user-guide.')->group(function () {
    Route::get('/guest', [UserGuideController::class, 'guest'])->name('guest');
    Route::get('/user', [UserGuideController::class, 'user'])->name('user');
    Route::get('/admin', [UserGuideController::class, 'admin'])->name('admin');
    Route::get('/manager', [UserGuideController::class, 'manager'])->name('manager');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Дашборд администратора
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Управление пользователями
    Route::prefix('admin/users')->name('admin.users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Управление категориями
    Route::prefix('admin/categories')->name('admin.categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::patch('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Управление продукцией
    Route::prefix('admin/products')->name('admin.products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/add-quantity', [ProductController::class, 'showAddQuantity'])->name('addQuantity');
        Route::post('/{product}/add-quantity', [ProductController::class, 'addQuantity'])->name('addQuantity.store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::patch('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Управление новинками
    Route::prefix('admin/featured')->name('admin.featured.')->group(function () {
        Route::get('/', [FeaturedController::class, 'index'])->name('index');
        Route::post('/add', [FeaturedController::class, 'add'])->name('add');
        Route::delete('/remove', [FeaturedController::class, 'remove'])->name('remove');
        Route::post('/reorder', [FeaturedController::class, 'reorder'])->name('reorder');
        Route::delete('/reset', [FeaturedController::class, 'reset'])->name('reset');
    });

    // Отчеты
    Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::post('/orders', [ReportController::class, 'orders'])->name('orders');
        Route::post('/finance', [ReportController::class, 'finance'])->name('finance');
        Route::post('/warehouse', [ReportController::class, 'warehouse'])->name('warehouse');
        Route::post('/products', [ReportController::class, 'products'])->name('products');
    });
});

// Дашборд менеджера
Route::middleware(['auth'])->group(function () {
    Route::get('/manager/dashboard', function () {
        return view('manager.dashboard');
    })->name('manager.dashboard');
    
    // Управление заказами
    Route::prefix('manager/orders')->name('manager.orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
    });

    // Управление складами
    Route::prefix('manager/warehouses')->name('manager.warehouses.')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('index');
        Route::get('/create', [WarehouseController::class, 'create'])->name('create');
        Route::post('/', [WarehouseController::class, 'store'])->name('store');
        Route::get('/{warehouse}', [WarehouseController::class, 'show'])->name('show');
        Route::get('/{warehouse}/movement-history', [WarehouseController::class, 'movementHistory'])->name('movementHistory');
        Route::get('/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('edit');
        Route::patch('/{warehouse}', [WarehouseController::class, 'update'])->name('update');
        Route::delete('/{warehouse}', [WarehouseController::class, 'destroy'])->name('destroy');
        Route::post('/{warehouse}/ingredients', [WarehouseController::class, 'addIngredient'])->name('addIngredient');
        Route::get('/{warehouse}/transfer', [WarehouseController::class, 'showTransferForm'])->name('transfer');
        Route::post('/{warehouse}/transfer', [WarehouseController::class, 'transfer'])->name('transfer.store');
    });

    // Управление ингредиентами
    Route::prefix('manager/ingredients')->name('manager.ingredients.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Manager\IngredientController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Manager\IngredientController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Manager\IngredientController::class, 'store'])->name('store');
        Route::get('/{ingredient}/edit', [\App\Http\Controllers\Manager\IngredientController::class, 'edit'])->name('edit');
        Route::patch('/{ingredient}', [\App\Http\Controllers\Manager\IngredientController::class, 'update'])->name('update');
        Route::delete('/{ingredient}', [\App\Http\Controllers\Manager\IngredientController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Каталог продукции для обычных пользователей
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/category/{category}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('catalog.show');

// Корзина
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove');
    Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('update');
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('auth');
});

// Оплата
Route::prefix('payment')->name('payment.')->group(function () {
    Route::post('/create', [PaymentController::class, 'create'])->name('create')->middleware('auth');
    Route::post('/order/{orderId}/pay', [PaymentController::class, 'payOrder'])->name('pay-order')->middleware('auth');
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
    Route::post('/webhook', [PaymentController::class, 'webhook'])->name('webhook');
});

require __DIR__.'/auth.php';
