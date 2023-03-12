<?php

// phpcs:disable Squiz.Objects.ObjectInstantiation.NotAssigned

namespace App\Http\Requests;

use App\Rules\UniquePriceNotification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use \Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class PriceNotificationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'price' => [
                'required',
                'numeric',
                'gt:0',
                new UniquePriceNotification($this->get('email'), $this->get('price')),
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
