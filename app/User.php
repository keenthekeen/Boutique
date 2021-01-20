<?php

namespace App;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;

/**
 * App\User
 *
 * @property string                                                     $id
 * @property string                                                     $name
 * @property string                                                     $email
 * @property string                                                     $avatar
 * @property bool                                                       $is_admin
 * @property bool                                                       $is_merchant
 * @property Carbon|null                                                $created_at
 * @property Carbon|null                                                $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read Collection|Order[]                                    $orders
 * @property-read Collection|Product[]                                  $products
 * @property-read Collection|Promotion[]                                $promotions
 * @method static Builder|User whereAvatar($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
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
        'is_merchant'
    ];
    
    protected $casts = [
        'is_admin' => 'boolean',
    ];
    
    public function products(): HasMany {
        return $this->hasMany('App\Product');
    }
    
    public function orders(): HasMany {
        return $this->hasMany('App\Order');
    }
    
    public function promotions(): HasMany {
        return $this->hasMany('App\Promotion');
    }
}
