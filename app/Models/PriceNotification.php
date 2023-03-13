<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 * @property float $price
 * @property boolean $active
 * @method static Builder where(string $field, $value)
 */
class PriceNotification extends Model
{

    /**
     * Holds the table name
     *
     * @var string
     */
    protected $table = 'price_notifications';

    /**
     * Holds the model fillable/populatable fields
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'price',
        'active',
    ];


    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }
}
