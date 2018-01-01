<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OrderItem
 *
 * @property int $id
 * @property string $order_id
 * @property string $product_item_id
 * @property float $price
 * @property int $quantity
 * @property-read \App\Order $order
 * @property-read \App\ProductItem $productItem
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereProductItemId($value)
 * @mixin \Eloquent
 */
class OrderItem extends Model {
    public $timestamps = false;
    protected $fillable = ['order_id', 'product_item_id', 'price', 'quantity'];
    
    public function order() {
        return $this->belongsTo('App\Order');
    }
    
    public function productItem() {
        return $this->belongsTo('App\ProductItem');
    }
}