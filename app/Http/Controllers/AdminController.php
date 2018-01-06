<?php

namespace App\Http\Controllers;

use App\Product;

class AdminController extends Controller {
    public function getProductList () {
        $products = Product::with('items')->select('id', 'name', 'author', 'type', 'picture')->orderBy('name')->get()->map(function (Product $product) {
            // Mutate required property
            $product->picture = $product->picture;
            return $product;
        });
        
        return response()->json(['books' => $products->where('type', 'หนังสือ'), 'non-books' => $products->where('type', '!=', 'หนังสือ')]);
    }
}