<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\User
 *
 * @property string                                                                                                         $id
 * @property string                                                                                                         $name
 * @property string                                                                                                         $email
 * @property string                                                                                                         $avatar
 * @property bool                                                                                                         $is_admin
 * @property \Carbon\Carbon|null                                                                                            $created_at
 * @property \Carbon\Carbon|null                                                                                            $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[]                                                     $orders
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[]                                                   $products
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Promotion[]                                                 $promotions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable {
    use Notifiable;
    
    public $incrementing = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'avatar',
    ];
    
    protected $casts = [
        'is_admin' => 'boolean',
    ];
    
    public function products() {
        return $this->hasMany('App\Product');
    }
    
    public function orders() {
        return $this->hasMany('App\Order');
    }
    
    public function promotions() {
        return $this->hasMany('App\Promotion');
    }
}
