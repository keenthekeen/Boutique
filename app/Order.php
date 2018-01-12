<?php

namespace App;

use Debugbar;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * App\Order
 *
 * @property int                                                            $id
 * @property string|null                                                    $user_id
 * @property string                                                         $type
 * @property string                                                         $status
 * @property float                                                          $price
 * @property string|null                                                    $payment_note
 * @property string|null                                                    $promotion
 * @property string|null                                                    $deleted_at
 * @property \Carbon\Carbon|null                                            $created_at
 * @property \Carbon\Carbon|null                                            $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrderItem[] $items
 * @property-read \App\User|null                                            $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Order onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePaymentNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePromotion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Order withoutTrashed()
 * @mixin \Eloquent
 */
class Order extends Model {
    use SoftDeletes;
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'promotion' => 'array'
    ];
    
    public function items() {
        return $this->hasMany('App\OrderItem');
    }
    
    public function user() {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Create order items
     * @param Collection $items Array of CartItem
     * @return array
     */
    public function addItems(Collection $items) {
        if (empty($this->id)) {
            $this->save();
        }
        $orderItems = [];
        foreach ($items as $item) {
            /** @var CartItem $item */
            $orderItems [] = OrderItem::create(['order_id' => $this->id, 'product_item_id' => $item->id, 'price' => (float)$item->subtotal(), 'quantity' => $item->qty]);
        }
        
        return $orderItems;
    }
    
    /**
     * Calculate price, promotions applied
     * @param Collection $cart
     * @return \Illuminate\Support\Collection
     */
    public static function processPromotions(Collection $cart) {
        // Future improvement: Use more efficient algorithm
        $basketA = collect($cart);
        $basketReduced = []; // [{item: ProductItem, quantity: int}]
        $promotionApplied = collect(); // [name => reducedPrice]
        
        // Loop through all promotion entry, check if
        foreach (Promotion::inRandomOrder()->get() as $promotion) {
            do {
                Debugbar::info('Processing promotion ' . $promotion->name);
                /** @var array<bool> $validation */
                $validation = [];
                $discount = 0;
                $basketANew = $basketA;
                $basketReducedNew = $basketReduced;
                foreach ($promotion->detail as $condition) {
                    foreach ($basketA as $item) {
                        /** @var $item CartItem */
                        if (!empty($item) AND $item->model->product->id == $condition['product'] AND $item->qty >= $condition['quantity']) {
                            $validation [] = true;
                            $discount += $condition['quantity'] * $condition['discount'];
                            $basketANew->transform(function ($i) use ($item, $condition) {
                                /** @var $i CartItem */
                                if ($i === $item) {
                                    if ($i->qty > $condition['quantity']) {
                                        $i->setQuantity($i->qty - $condition['quantity']);
                                    } else {
                                        return NULL;
                                    }
                                }
                                
                                return $i;
                            });
                            if (empty($basketReducedNew[$item->id])) {
                                $basketReducedNew[$item->id] = $condition['quantity'];
                            } else {
                                $basketReducedNew[$item->id] += $condition['quantity'];
                            }
                        } else {
                            $validation [] = false;
                        }
                    } // End foreach item
                } // End foreach condition
                if (!empty($validation) AND !in_array(false, $validation)) {
                    // If promotion can be applied
                    $basketA = $basketANew;
                    $basketReduced = $basketReducedNew;
                    if (empty($oldApp = $promotionApplied->where('promotion', $promotion)->all())) {
                        $promotionApplied->push(['promotion' => $promotion, 'reduced' => $discount, 'times' => 1]);
                    } else {
                        $promotionApplied->transform(function ($i) use ($promotion, $discount) {
                            if ($i['promotion'] == $promotion) {
                                $i['reduced'] += $discount;
                                $i['times']++;
                            }
                            
                            return $i;
                        });
                    }
                }
            } while (!empty($validation) AND !in_array(false, $validation)); // If promotion has been applied, check the condition again to see if it can be applied more than once.
        }
        
        return $promotionApplied;
    }
}