<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function getProductList() {
        $products = Product::with('items')->select('id', 'name', 'author', 'type', 'picture')->orderBy('name')->get()->map(function (Product $product) {
            $product->name = $product->type . ' ' . $product->name;
            // Mutate required attribute
            $product->picture = $product->picture;
            
            return $product;
        });
        
        return response()->json(['books' => $products->where('type', 'หนังสือ'), 'non-books' => $products->where('type', '!=', 'หนังสือ')]);
    }
    
    public function processCashier (Request $request) {
        $cartContent = array();
        foreach ($request->input('cart') as $cart) {
        
        }
        $cartContent = $this->cart->content();
        $appliedPromotions = Order::processPromotions($cartContent);
        $discountSum = $appliedPromotions->pluck('reduced')->sum();
        $total = $this->cart->subtotal() - abs($discountSum);
        if ($request->input('total') != $total) {
            return back()->withErrors('Error occurred! Please try again.');
        }
    
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->type = 'cash';
        $order->status = 'unpaid';
        $order->price = $total;
        $order->promotion = $appliedPromotions->map(function ($i) {
            $i['promotion'] = $i['promotion']->id;
        
            return $i;
        });
        $order->addItems($cartContent);
        $order->save();
    
        $this->cart->destroy();
    
        return redirect('/cart/order/' . $order->id);
    }
}