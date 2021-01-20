<?php

namespace App;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
 * @property array                         $book_subject
 * @property string                        $user_id
 * @property mixed                         $owner_detail_1
 * @property mixed                         $owner_detail_2
 * @property array                         $payment
 * @property string                        $status
 * @property string|null                   $note
 * @property string|null                   $deleted_at
 * @property Carbon|null                   $created_at
 * @property Carbon|null                   $updated_at
 * @property-read Collection|ProductItem[] $items
 * @property-read User                     $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Product whereAuthor($value)
 * @method static Builder|Product whereBookExample($value)
 * @method static Builder|Product whereBookSubject($value)
 * @method static Builder|Product whereBookType($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDeletedAt($value)
 * @method static Builder|Product whereDetail($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product whereNote($value)
 * @method static Builder|Product whereOwnerDetail1($value)
 * @method static Builder|Product whereOwnerDetail2($value)
 * @method static Builder|Product wherePayment($value)
 * @method static Builder|Product wherePicture($value)
 * @method static Builder|Product wherePoster($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereStatus($value)
 * @method static Builder|Product whereType($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @method static Builder|Product whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin Eloquent
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
        return $this->items->where('type', 'NORMAL')->map(function ($item) {
            /** @var $item ProductItem */
            $item->amountLeft = $item->getAmountLeft();
            return $item;
        })->sum('amountLeft') > 0;
    }
    
    public function getShortNote(): string {
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
    public function createInput($id, $name, $isRequired = false, $max = 50, $type = "text"): string {
        $identifier = ucfirst(substr(md5($id), 0, 5));
        
        return '<input id="i' . $identifier . '" name="' . Helper::eloquentToInputName($id) . '" type="' . $type . '" class="validate" value="' . $this->getOldInput($id) . '" ' . ($isRequired ? 'required' : '') . ($type == 'number' ? ' min="0" step="1" max="' : ' data-length="') . $max . '"/><label for="i' . $identifier . '">' . $name . '</label>';
    }
    
    /**
     * Generate html <option> from [value] or [option=>value].
     *
     * @param string $id
     * @param string $name
     * @param array  $options
     * @param bool   $required
     * @param bool   $multiple
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
        return old($id, $this->getAttributeAsString($id));
    }
    
    public function getAttributeAsString ($id): string {
        if (Str::contains($id, '.')) {
            $separatedId = explode('.', $id);
            $val = $this->{$separatedId[0]} ?? array();
            foreach ($separatedId as $key => $value) {
                if ($key > 0) {
                    if (array_key_exists($value, $val)) {
                        return $val[$value];
                    } else {
                        return '';
                    }
                }
            }
        }
    
        return $this->$id ?? '';
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

    public function getUnitName(): string {
        switch($this->type){
            case 'สมุด':
            case 'หนังสือ': return 'เล่ม';
            case 'กระเป๋า': return 'ใบ';
            case 'เสื้อ': return 'ตัว';
            case 'พวงกุญแจ': return 'พวง';
            case 'ริสแบนด์':
            case 'แฟ้ม':
            default: return 'อัน';
        }
    }
}
