<?php

// phpcs:disable Squiz.Strings.DoubleQuoteUsage.ContainsVar
// phpcs:disable Squiz.WhiteSpace.ObjectOperatorSpacing.Before

namespace App\Rules;

use App\Models\PriceNotification;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString as PotentiallyTranslatedString;

class UniquePriceNotification implements ValidationRule
{
    public function __construct(
        private readonly ?string $email,
        private readonly ?float $price
    ) { }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = PriceNotification::where('email', $this->email)
            ->where('price', round($this->price, 2))
            ->exists();

        if ($exists === true) {
            $fail("Already the email '$this->email' for the price '$this->price'");
        }
    }
}
