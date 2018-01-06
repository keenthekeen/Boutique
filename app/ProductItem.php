<?php

namespace App;

use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\ProductItem
 *
 * @property int                                                            $id
 * @property int                                                            $product_id
 * @property string                                                         $name
 * @property float                                                          $price
 * @property int                                                            $amount
 * @property \Carbon\Carbon|null                                            $created_at
 * @property \Carbon\Carbon|null                                            $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrderItem[] $orderItems
 * @property-read \App\Product                                              $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductItem extends Model implements Buyable {
    public function product() {
        return $this->belongsTo('App\Product');
    }
    
    public function orderItems() {
        return $this->hasMany('App\OrderItem');
    }
    
    public function colorCode() {
        return Helper::materialColor($this->id);
    }
    
    // Implements Cart's Buyable
    
    /**
     * Get the identifier of the Buyable item.
     *
     * @param null $options
     * @return int|string
     */
    public function getBuyableIdentifier($options = NULL) {
        return $this->id;
    }
    
    /**
     * Get the description or title of the Buyable item.
     *
     * @param null $options
     * @return string
     */
    public function getBuyableDescription($options = NULL) {
        return $this->name;
    }
    
    /**
     * Get the price of the Buyable item.
     *
     * @param null $options
     * @return float
     */
    public function getBuyablePrice($options = NULL) {
        return $this->price;
    }
}