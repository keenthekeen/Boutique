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

Route::view('/', 'home')->name('home');
Route::get('product/{product}', function (\App\Product $product) {
    return view('product', ['product' => $product]);
});

Route::get('login', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('login/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('debug/user', function () {
    if (Auth::check()) {
        return response()->json(Auth::user());
    } else {
        return response('Unauthenticated');
    }
});

Route::prefix('merchant')->middleware(['auth'])->group(function () {
    // Merchant
    Route::view('register', 'merchant-register');
    Route::get('edit/{product}', function (\App\Product $product) {
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
});
