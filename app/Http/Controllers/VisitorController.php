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
    
    public function addToCart (ProductItem $item) {
        $this->cart->add($item);
        return redirect('/cart')->with('notify', '<span>เพิ่มในตะกร้าแล้ว</span><a class="btn-flat toast-action" href="/">ดูสินค้าอื่นๆ</a>');
    }
    
    public function removeFromCart ($rowId) {
        $this->cart->remove($rowId);
        return back()->with('notify', 'ลบจากตะกร้าแล้ว');
    }
    
    public function updateCart ($rowId, $quantity) {
        $this->cart->update($rowId, $quantity);
        return back()->with('notify', 'ปรับจำนวนสินค้าแล้ว');
    }
    
    public function checkout (Request $request) {
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
        $order->price = $total;
        $order->promotion = $appliedPromotions;
        $order->addItems($cartContent);
        $order->save();
    }
    
}