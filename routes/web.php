<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'home')->name('home')->middleware('cache');
Route::view('print', 'print-catalog');
Route::redirect('home', '/');
Route::get('product/{product}', function (\App\Product $product) {
    return view('product', ['product' => $product]);
})->middleware('cache');

// Project - specific data
Route::redirect('contact', 'https://www.facebook.com/TriamUdomOPH/');

Route::get('login', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('login/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('logout', 'Auth\LoginController@logout');

if (!env('SHOP_CLOSED', false)) {
    Route::prefix('merchant')->middleware(['auth'])->group(function () {
        // Merchant
        Route::view('register', 'merchant-register');
        Route::get('edit/{product}', function (\App\Product $product) {
            if ($product->user_id != Auth::id() AND !Auth::user()->is_admin) {
                abort(403);
            }
            
            return view('merchant-register', ['product' => $product]);
        });
        Route::post('register', 'MerchantController@registerProduct');
    });
    
    Route::prefix('cart')->middleware(['auth'])->group(function () {
        // Visitor
        Route::view('/', 'cart.cart');
        Route::get('add/{item}', 'VisitorController@addToCart');
        Route::get('remove/{rowId}', 'VisitorController@removeFromCart');
        Route::get('update/{rowId}/{quantity}', 'VisitorController@updateCart');
        Route::post('checkout', 'VisitorController@checkout');
        Route::get('order/{order}', function (\App\Order $order) {
            if ($order->user_id == Auth::id()) {
                return view('cart.view', ['order' => $order]);
            } else {
                return response()->view('errors.403', [], 403);
            }
        })->name('cart.order');
        Route::post('pay', 'VisitorController@pay');
        Route::post('pay/card', 'VisitorController@payByCard');
        Route::get('pay/{order}/check', 'VisitorController@checkCardPayment')->name('cart.paycomplete');
    });
}

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::view('cashier', 'admin.cashier')->middleware('cache');
    Route::get('products', 'AdminController@getProductList')->middleware('cache');
    Route::post('cashier', 'AdminController@processCashier');
    Route::get('delivery/{mode?}', 'AdminController@viewDeliver');
    Route::post('delivery', 'AdminController@deliverOrder');
    Route::view('inventory', 'admin.inventory');
    Route::any('find-order', function () {
        return view('admin.find-order');
    });
    Route::view('paycheck', 'admin.paycheck');
});

if (config('app.debug')) {
    Route::prefix('debug')->group(function () {
        Route::get('view/{view}', function ($view) {
            return view($view);
        });
    });
}
Route::get('user-id', function () {
    return response('User ID: ' . Auth::id());
})->middleware('auth');