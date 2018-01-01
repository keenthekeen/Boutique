<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Promotion
 *
 * @property int $id
 * @property array $detail {product, quantity, discount}
 * @property string $name
 * @property string $user_id
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Promotion onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Promotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Promotion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Promotion whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Promotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Promotion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Promotion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Promotion whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Promotion withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Promotion withoutTrashed()
 * @mixin \Eloquent
 */
class Promotion extends Model {
    use SoftDeletes;
    
    protected $fillable = ['detail', 'name', 'user_id'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'detail' => 'array',
    ];
    
    public function user() {
        return $this->belongsTo('App\User');
    }
}