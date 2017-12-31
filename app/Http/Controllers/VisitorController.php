<?php

namespace App\Http\Controllers;

use App\ProductItem;
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
    
    }
    
}