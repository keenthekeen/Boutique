<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Sale
 *
 * @property int $id
 * @property string|null $buyer
 * @property string $product
 * @property string $price
 * @property string $payment
 * @property string|null $payment_note
 * @property string|null $note
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sale whereBuyer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sale whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sale wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sale wherePaymentNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sale wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sale whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sale whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sale extends Model {

}