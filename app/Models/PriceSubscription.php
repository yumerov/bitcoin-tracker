<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 * @property float $price
 * @property float|null $deactivated_at
 * @property boolean $active
 * @method static Builder where(string $field, $operatorOrValue, $value = null)
 */
class PriceSubscription extends Model
{

    /**
     * Holds the model fillable/populate fields
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'price',
        'deactivated_at',
        'active',
    ];


    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }
}
