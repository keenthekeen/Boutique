<?php

namespace App;

use Carbon\Carbon;
use Eloquent;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\ProductItem
 *
 * @property int                         $id
 * @property int                         $product_id
 * @property string                      $type
 * @property string                      $name
 * @property float                       $price
 * @property int                         $amount
 * @property Carbon|null                 $created_at
 * @property Carbon|null                 $updated_at
 * @property-read Collection|OrderItem[] $orderItems
 * @property-read Product                $product
 * @method static Builder|ProductItem whereAmount($value)
 * @method static Builder|ProductItem whereCreatedAt($value)
 * @method static Builder|ProductItem whereId($value)
 * @method static Builder|ProductItem whereName($value)
 * @method static Builder|ProductItem wherePrice($value)
 * @method static Builder|ProductItem whereProductId($value)
 * @method static Builder|ProductItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ProductItem extends Model implements Buyable {
    protected $guarded = ['updated_at'];
    public $amountLeft = NULL;
    
    public function product() {
        return $this->belongsTo('App\Product');
    }
    
    public function orderItems() {
        return $this->hasMany('App\OrderItem');
    }
    
    public function colorCode(): string {
        return Helper::materialColor($this->id);
    }
    
    public function getAmountSold(): int {
        return $this->orderItems()->whereHas('order', function ($query) {
            $query->where('status', '!=', 'unpaid');
        })->sum('quantity');
    }
    
    public function getAmountLeft(): int {
        return $this->amount - $this->getAmountSold();
    }
    
    // Implements Cart's Buyable
    
    /**
     * Get the identifier of the Buyable item.
     *
     * @param null $options
     * @return int|string
     */
    public function getBuyableIdentifier($options = NULL): int {
        return $this->id;
    }
    
    /**
     * Get the description or title of the Buyable item.
     *
     * @param null $options
     * @return string
     */
    public function getBuyableDescription($options = NULL): string {
        return $this->name;
    }
    
    /**
     * Get the price of the Buyable item.
     *
     * @param null $options
     * @return float
     */
    public function getBuyablePrice($options = NULL): float {
        return $this->price;
    }
    
    /**
     * Get the weight of the Buyable item.
     *
     * @param null $options
     * @return float
     */
    public function getBuyableWeight($options = null): float {
        return 1;
    }
}
