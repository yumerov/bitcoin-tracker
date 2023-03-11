<?php

namespace App\Models;

use Carbon\Carbon as Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|null $id
 * @property float|null $price
 * @property price|null $timestamp
 */
class Price extends Model
{
    protected $fillable = [
        'price',
        'timestamp'
    ];

    protected array $dates = [
        'timestamp'
    ];

    public $timestamps = false;

    public function setTimestampAttribute($value)
    {
        $this->attributes['timestamp'] = Carbon::parse($value);
    }

    public function getTimestampAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
