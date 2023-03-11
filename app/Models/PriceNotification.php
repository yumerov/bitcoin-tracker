<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder where(string $field, $value)
 */
class PriceNotification extends Model
{
    protected $table = 'price_notifications';

    protected $fillable = ['email', 'price', 'active'];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }
}
