<?php

namespace App\Http\Controllers;

use App\Order;
use App\ProductItem;
use Auth;
use Gloudemans\Shoppingcart\Cart;
use Illuminate\Http\Request;

class VisitorController extends Controller {
    protected $cart;
    
    public function __construct(Cart $cart) {
        $this->cart = $cart;
    }
    
    public function addToCart(ProductItem $item) {
        $this->cart->add($item);
        
        return redirect('/cart')->with('notify', '<span>เพิ่มในตะกร้าแล้ว</span><a class="btn-flat toast-action" href="/">ดูสินค้าอื่นๆ</a>');
    }
    
    public function removeFromCart($rowId) {
        $this->cart->remove($rowId);
        
        return back()->with('notify', 'ลบจากตะกร้าแล้ว');
    }
    
    public function updateCart($rowId, $quantity) {
        $this->cart->update($rowId, $quantity);
        
        return back()->with('notify', 'ปรับจำนวนสินค้าแล้ว');
    }
    
    public function checkout(Request $request) {
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
    
    public function pay (Request $request) {
        $this->validate($request, [
            'order_id' => 'required',
            'payment_method' => 'required|in:promptpay,truemoney,truekiosk,line'
        ]);
        $order = Order::find($request->input('order_id'));
        if ($order->user_id != $request->user()->id OR $order->status != 'unpaid') {
            return response()->view('errors.403');
        }
        if (in_array($request->input('payment_method'), ['promptpay', 'truekiosk'])) {
            $this->validate($request, [
                'date' => 'required|string|max:30',
                'time' => 'required|string|max:30',
                'amount' => 'required_if:payment_method,truekiosk|max:100000'
            ]);
            $payment_note = [
                'date' => $request->date,
                'time' => $request->time,
                'amount' => $request->input('amount', $order->amountForTransfer())
            ];
        }
        $payment_note['method'] = strtoupper($request->payment_method);
        $payment_note['status'] = 'unverified';
        $payment_note['reported_time'] = date(DATE_ISO8601);
        $order->payment_note = $payment_note;
        $order->save();
        
        return redirect()->route('cart.order', ['order' => $order->id]);
    }
    
}