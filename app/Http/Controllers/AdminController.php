<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Product;
use App\ProductItem;
use Auth;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Http\Request;
use Validator;

class AdminController extends Controller {
    public function getProductList() {
        $products = Product::with('items')->select('id', 'name', 'author', 'type', 'picture')->orderBy('type')->orderBy('name')->get()->map(function (Product $product) {
            $product->name = $product->type . ' ' . $product->name . ($product->inStock() ? '' : '[หมด]');
            // Mutate required attribute
            $product->picture = $product->picture;
            $product->items->transform(function ($item) {
                /** @var $item ProductItem */
                $item->name = $item->name . (($item->getAmountLeft() > 0) ? '' : '[หมด]');
                return $item;
            });
            
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
            if ($request->has('checker') AND !empty($request->input('checker')) AND $request->input('checker') != 'DEFAULT') {
                $previousOrders = Order::where('status', 'paid')->whereRaw('updated_at >= DATE_SUB(NOW(),INTERVAL 1 HOUR)')->get();
                foreach ($previousOrders as $pOrder) {
                    if (is_array($pOrder->payment_note) AND !empty($pOrder->payment_note['checker']) AND $pOrder->payment_note['checker'] === $request->input('checker')) {
                        return response(json_encode([
                            'status' => 'calculated',
                            'cart' => $request->input('cart'),
                            'promotions' => $appliedPromotions,
                            'discount' => 0,
                            'sum' => 0,
                            'total' => 'Order existed: '.$pOrder->id
                        ]));
                    }
                }
            }

            $method = $request->get('method', 'cash');

            $order = new Order();
            $order->type = $method;
            $order->status = 'paid';
            $order->price = $total;
            $order->promotion = $appliedPromotions->map(function ($i) {
                $i['promotion'] = $i['promotion']->id;
                
                return $i;
            });
            $order->payment_note = [
                'method' => ($method == 'cash') ? 'CASH' : (($method == 'promptpay') ? 'PROMPTPAY' : 'UNKNOWN'),
                'customer_type' => 'walkin',
                'cashier' => Auth::id(),
                'paid_time' => date(DATE_ISO8601),
                'checker' => $request->input('checker')
            ];
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
            // Calculate price but don't save
            return response(json_encode([
                'status' => 'calculated',
                'cart' => $request->input('cart'),
                'promotions' => $appliedPromotions,
                'discount' => $discountSum,
                'sum' => $totalValue,
                'total' => $total,
                'checker' => md5($total.microtime().Auth::id())
            ]));
        }
    }
    
    public function viewDeliver(Request $request, $mode = 'all') {
        $undelivers = Order::with('items')->where('status', 'paid');
        if ($mode == 'latest') {
            $undelivers = $undelivers->whereRaw('updated_at >= DATE_SUB(NOW(),INTERVAL 30 MINUTE)')->get();
        } else {
            $undelivers = $undelivers->paginate(30);
            $links = $undelivers->links();
        }
        
        $pending = [];
        
        foreach ($undelivers as $undeliver) {
            $items = $undeliver->items;
            $orderSum = $items->sum('price');
            $pending[$undeliver->id] = [
                'time' => ($undeliver->created_at ?? Carbon::create())->toDateTimeString(),
                'items' => $items->map(function (OrderItem $item) use ($orderSum) {
                    $pI = $item->productItem;
                    return ['id' => $pI->product_id, 'name' => $pI->name, 'quantity' => $item->quantity, 'price' => $item->price];
                })->all(),
                'total' => $undeliver->price,
                'isPriceMatch' => $undeliver->price == $orderSum,
                'method' => $undeliver->payment_note['method']
            ];
        }
        
        return response()->view('admin.delivery', ['list' => $pending, 'mode' => $mode, 'links' => $links ?? '']);
    }
    
    public function deliverOrder(Request $request) {
        $this->validate($request, ['order' => 'required']);
        /** @var Order $order */
        $order = Order::findOrFail($request->input('order'));
        $order->status = 'delivered';
        $order->save();

        return back()->with('notify', 'Delivered order '.$order->id);
    }

    public function addStock(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'type' => 'required|in:NORMAL,PROMOTION',
            'name' => 'required',
            'amount' => 'required|numeric',
            'price' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 100]);
        }

        $productItem = ProductItem::where('product_id', $request->get('id'))
            ->where('name', $request->get('name'))->first();

        if (is_null($productItem)) {
            $productItem = new ProductItem();
            $productItem->product_id = $request->get('id');
            $productItem->name = $request->get('name');
            $productItem->amount = $request->get('amount');
            $productItem->price = $request->get('price');
            $productItem->type = $request->get('type');
        }
        else{
            $productItem->price = $request->get('price');
            $productItem->amount += $request->get('amount');
        }

        $productItem->save();

        return response()->json(['code' => 200]);
    }
}