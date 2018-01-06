<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Product
 *
 * @property int                                                              $id
 * @property string                                                           $name
 * @property string                                                           $author
 * @property string                                                           $type
 * @property float                                                            $price
 * @property array                                                            $detail
 * @property string|null                                                      $picture
 * @property string|null                                                      $poster
 * @property string|null                                                      $book_example
 * @property string|null                                                      $book_type
 * @property array                                                            $book_subject
 * @property string                                                           $user_id
 * @property mixed                                                            $owner_detail_1
 * @property mixed                                                            $owner_detail_2
 * @property array                                                            $payment
 * @property string                                                           $status
 * @property string|null                                                      $note
 * @property string|null                                                      $deleted_at
 * @property \Carbon\Carbon|null                                              $created_at
 * @property \Carbon\Carbon|null                                              $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductItem[] $items
 * @property-read \App\User                                                   $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Product onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBookExample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBookSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBookType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereOwnerDetail1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereOwnerDetail2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePoster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Product withoutTrashed()
 * @mixin \Eloquent
 */
class Product extends Model {
    use SoftDeletes;
    
    protected $fillable = ['name', 'author', 'type', 'price', 'detail', 'book_type', 'book_subject', 'owner_detail_1', 'owner_detail_2', 'payment', 'note'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'detail' => 'array',
        'book_subject' => 'array',
        'owner_detail_1' => 'array',
        'owner_detail_2' => 'array',
        'payment' => 'array',
        'picture' => 'local_file',
        'poster' => 'local_file',
        'book_example' => 'local_file',
    ];
    
    public function items() {
        return $this->hasMany('App\ProductItem');
    }
    
    public function user() {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Is available for sale?
     * @return bool
     */
    public function inStock(): bool {
        // @TODO implement this!
        $stock = 0;
        $sold = 0;
        foreach ($this->items as $item) {
            $stock += $item->amount;
            $sold += $item->orderItems()->sum('quantity'); // UNPAID included!
        }
        
        return $stock*1.5 > $sold;
    }
    
    public function getShortNote() {
        $separated = explode('/', $this->note);
        
        return $separated[0];
    }
    
    
    /**
     * Create HTML input for merchant registration form
     *
     * @param        $id
     * @param        $name
     * @param bool   $isRequired
     * @param int    $max
     * @param string $type
     * @return string
     */
    public function createInput($id, $name, $isRequired = false, $max = 50, $type = "text") {
        $identifier = ucfirst(substr(md5($id), 0, 5));
        
        return '<input id="i' . $identifier . '" name="' . Helper::eloquentToInputName($id) . '" type="' . $type . '" class="validate" value="' . $this->getOldInput($id) . '" ' . ($isRequired ? 'required' : '') . ($type == 'number' ? ' min="0" step="1" max="' : ' data-length="') . $max . '"/><label for="i' . $identifier . '">' . $name . '</label>';
    }
    
    /**
     * Generate html <option> from [value] or [option=>value].
     *
     * @param       $id
     * @param array $options
     * @param bool  $required
     * @param bool  $multiple
     * @return string
     */
    public function createOption(string $id, string $name, array $options, $required = false, $multiple = false): string {
        $oldValue = $this->getOldInput($id);
        if (substr($id, -1, 1) == '.') {
            $oldValue = $this->{substr($id, 0, strlen($id) - 1)};
        }
        $html = '<select name="' . Helper::eloquentToInputName($id) . '"' . ($multiple ? ' multiple' : '') . ($required ? ' required' : '') . '>';
        $html .= '<option disabled>เลือก</option>';
        foreach ($options as $title => $value) {
            $selected = is_array($oldValue) ? in_array($value, $oldValue) : ($value == $oldValue);
            $html .= '<option value="' . $value . '"' . ($selected ? ' selected>' : '>') . (is_numeric($title) ? $value : $title) . '</option>';
        }
        $html .= '</select><label>' . $name . '</label>';
        
        return $html;
    }
    
    public function getOldInput($id) {
        if (str_contains($id, '.')) {
            $separatedId = explode('.', $id);
            $val = $this->{$separatedId[0]} ?? array();
            foreach ($separatedId as $key => $value) {
                if ($key > 0) {
                    if (array_key_exists($value, $val)) {
                        $val = $val[$value];
                    } else {
                        return old($id);
                    }
                }
            }
            
            return old($id, $val);
        }
        
        return old($id, $this->$id ?? '');
    }
    
    /**
     * Get an attribute from the model.
     *
     * @param  string $key
     * @return mixed
     */
    public function getAttribute($key) {
        if (array_key_exists($key, $this->casts) AND $this->casts[$key] == 'local_file') {
            if (empty($this->attributes[$key])) {
                return NULL;
            }
            
            return url('/storage/product/' . basename($this->attributes[$key]));
        }
        
        return parent::getAttribute($key);
    }
}