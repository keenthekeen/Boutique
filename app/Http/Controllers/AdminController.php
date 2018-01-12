<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Product;
use Carbon\Carbon;
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
            try {
                $order->addItems($cartContent);
                $order->save();
            } catch (\Exception $e) {
                $order->items()->delete();
                try {
                    $order->delete();
                } catch (\Exception $e) {
                    return response(json_encode([
                        'status' => 'calculated',
                        'cart' => $request->input('cart'),
                        'promotions' => $appliedPromotions,
                        'discount' => 0,
                        'sum' => 0,
                        'total' => $e->getMessage()
                    ]));
                }
    
                return response(json_encode([
                    'status' => 'calculated',
                    'cart' => $request->input('cart'),
                    'promotions' => $appliedPromotions,
                    'discount' => 0,
                    'sum' => 0,
                    'total' => $e->getMessage()
                ]));
            }
            
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
    
    public function getUndeliver(Request $request) {
        $undelivers = Order::with('items')->where('status', 'paid');
        /*if ($request->has('all')) {
            $undelivers = $undelivers->whereTime('updated_at', '>=', Carbon::now()->subHours(4)->toIso8601String());
        }*/
        $undelivers = $undelivers->get();
        
        $pending = [];
        
        foreach ($undelivers as $undeliver) {
            $items = $undeliver->items;
            $orderSum = $items->sum('price');
            $pending[$undeliver->id] = [
                'items' => $items->map(function (OrderItem $item) use ($orderSum) {
                    $pI = $item->productItem;
                    return ['id' => $pI->product_id, 'name' => $pI->name, 'quantity' => $item->quantity, 'price' => $item->price];
                })->all(),
                'total' => $undeliver->price,
                'isPriceMatch' => $undeliver->price == $orderSum
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