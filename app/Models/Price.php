<?php

// phpcs:disable Squiz.Arrays.ArrayDeclaration.MultiLineNotAllowed

namespace App\Models;

use Carbon\Carbon as Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|null $id
 * @property float|null $price
 * @property price|null $timestamp
 * @method static Builder orderBy(string $field, string $order)
 */
class Price extends Model
{

    /**
     * Holds the model fillable/populatable fields
     *
     * @var string[]
     */
    protected $fillable = [
        'price',
        'timestamp',
    ];

    /**
     * Holds fields type timestamp
     *
     * @var array|string[]
     */
    protected array $dates = [
        'timestamp',
    ];

    /**
     * Disables audit timestamps for this model
     *
     * @var boolean
     */
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
