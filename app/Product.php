<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Product
 *
 * @property int $id
 * @property string $name
 * @property bool $has_picture
 * @property bool $has_poster
 * @property string $author
 * @property string $facebook_url
 * @property string $description
 * @property string $type
 * @property float $price
 * @property int $amount
 * @property string $book_type
 * @property string $book_subject
 * @property int $book_page
 * @property int $book_question
 * @property string $person
 * @property string $telephone
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBookPage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBookQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBookSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBookType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereFacebookUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereHasPicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereHasPoster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereType($value)
 * @mixin \Eloquent
 */
class Product extends Model {
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_picture' => 'boolean',
        'has_poster' => 'boolean',
    ];
}