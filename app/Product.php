<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Product
 *
 * @property int $id
 * @property string $name
 * @property string $picture
 * @property string $author
 * @property string $type
 * @property float $price
 * @property mixed $detail
 * @property string|null $book_type
 * @property string|null $book_subject
 * @property mixed $book_detail
 * @property string $user_id
 * @property mixed $owner_detail_1
 * @property mixed $owner_detail_2
 * @property mixed $payment
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductItem[] $items
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Product onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBookDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBookSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBookType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereOwnerDetail1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereOwnerDetail2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Product withoutTrashed()
 * @mixin \Eloquent
 */
class Product extends Model {
    use SoftDeletes;
    
    public function items() {
        return $this->hasMany('App\ProductItem');
    }
    
    public function user() {
        return $this->belongsTo('App\User');
    }
}