<?php

namespace App\Http\Controllers;

use App\OmiseCharge;
use App\Order;
use App\ProductItem;
use Auth;
use Gloudemans\Shoppingcart\Cart;
use Gloudemans\Shoppingcart\CartItem;
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
        $total = $cartContent->sum(function (CartItem $item) {
            return $item->qty * $item->price;
        }) - abs($discountSum);
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
    
    public function pay(Request $request) {
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
                'amount' => number_format($request->input('amount', $order->amountForTransfer()), 2)
            ];
        }
        $payment_note['method'] = strtoupper($request->payment_method);
        $payment_note['status'] = 'unverified';
        $payment_note['reported_time'] = date(DATE_ISO8601);
        $order->payment_note = $payment_note;
        $order->status = 'pending';
        $order->save();
        
        return redirect()->route('cart.order', ['order' => $order->id]);
    }
    
    public function payByCard(Request $request) {
        $this->validate($request, ['order_id' => 'required', 'omise_token' => 'required']);
        $order = Order::find($request->input('order_id'));
        if ($order->user_id != $request->user()->id OR $order->status != 'unpaid') {
            return response()->view('errors.403');
        }
        $charge = OmiseCharge::chargeCard($order->price, $request->input('omise_token'), $order->id);
        $order->payment_note = $charge->export();
        $order->status = 'pending';
        if ($charge->isSuccess()) {
            $order->status = 'paid';
            $order->save();
            
            return redirect()->route('cart.order', ['order' => $order->id]);
        } elseif ($charge->getAuthorizeUri()) {
            $order->save();
            
            return redirect($charge->getAuthorizeUri());
        } elseif ($charge->getErrorMessage()) {
            $order->status = 'unpaid';
            $order->save();
            
            return back()->withErrors($charge->getErrorMessage());
        }
    }
    
    public function checkCardPayment(Request $request, Order $order) {
        // Note: User ID will not be matched with Order.
        if ($order->status != 'delivered' AND !empty($order->payment_note['charge_id'])) {
            $charge = OmiseCharge::retrieve($order->payment_note['charge_id']);
            $order->payment_note = $charge->export();
            if ($charge->isVoided()) {
                $order->status = 'unpaid';
                $order->save();
                return redirect()->route('cart.order', ['order' => $order->id])->with('notify', 'Transaction voided');
            } elseif ($charge->isSuccess()) {
                $order->status = 'paid';
                $order->save();
            } elseif ($charge->getAuthorizeUri()) {
                $order->save();
                
                return redirect($charge->getAuthorizeUri());
            } elseif ($charge->getErrorMessage()) {
                $order->status = 'unpaid';
                $order->save();
                
                return redirect()->route('cart.order', ['order' => $order->id])->withErrors($charge->getErrorMessage());
            }
        }
        
        return redirect()->route('cart.order', ['order' => $order->id]);
    }
}