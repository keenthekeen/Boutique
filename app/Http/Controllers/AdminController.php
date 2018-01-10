<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Product;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function getProductList() {
        $products = Product::with('items')->select('id', 'name', 'author', 'type', 'picture')->orderBy('type')->orderBy('name')->get()->map(function (Product $product) {
            $product->name = $product->type . ' ' . $product->name;
            // Mutate required attribute
            $product->picture = $product->picture;
            
            return $product;
        });
        
        return response()->json(['books' => $products->where('type', 'หนังสือ'), 'non-books' => $products->where('type', '!=', 'หนังสือ')]);
    }
    
    public function processCashier(Request $request) {
        $cartContent = collect();
        foreach ($request->input('cart') as $cart) {
            $cartItem = new CartItem($cart['id'], $cart['name'], $cart['price']);
            $cartItem->setQuantity($cart['quantity']);
            $cartItem->associate('\App\ProductItem');
            $cartContent->push($cartItem);
        }
        
        $totalValue = $cartContent->sum(function (CartItem $item) {
            return $item->qty * $item->price;
        });
        $appliedPromotions = Order::processPromotions($cartContent);
        $discountSum = $appliedPromotions->pluck('reduced')->sum();
        $total = $totalValue - abs($discountSum);
        
        if ($request->input('proceed') == 'true') {
            $order = new Order();
            $order->type = 'cash';
            $order->status = 'paid';
            $order->price = $total;
            $order->promotion = $appliedPromotions->map(function ($i) {
                $i['promotion'] = $i['promotion']->id;
                
                return $i;
            });
            $order->payment_note = 'CASH-U' . \Auth::id();
            $order->addItems($cartContent);
            $order->save();
            
            return response(json_encode([
                'status' => 'checked',
                'order_id' => $order->id,
                'order_time' => $order->created_at->toIso8601String(),
                'cart' => $request->input('cart'),
                'promotions' => $appliedPromotions,
                'discount' => $discountSum,
                'sum' => $totalValue,
                'total' => $total
            ]));
        } else {
            return response(json_encode([
                'status' => 'calculated',
                'cart' => $request->input('cart'),
                'promotions' => $appliedPromotions,
                'discount' => $discountSum,
                'sum' => $totalValue,
                'total' => $total
            ]));
        }
    }
    
    public function getUndeliver() {
        $undelivers = Order::with('items')->where('status', 'paid')->get();
        
        $pending = [];
        
        foreach ($undelivers as $undeliver) {
            $pending[$undeliver->id] = [
                'items' => $undeliver->items->map(function (OrderItem $item) {
                    return ['name' => $item->productItem->name, 'quantity' => $item->quantity];
                })->all(),
                'total' => $undeliver->price
            ];
        }
        
        return response()->view('admin.delivery', ['list' => $pending]);
    }
    
    public function deliverOrder(Request $request) {
        $this->validate($request, ['order' => 'required']);
        /** @var Order $order */
        $order = Order::findOrFail($request->input('order'));
        $order->status = 'delivered';
        $order->save();
        
        return redirect('/admin/delivery');
    }
}