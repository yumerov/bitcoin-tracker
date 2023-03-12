<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\JsonResponse;

class PriceHistoryController extends BaseController
{

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(Price::orderBy('timestamp', 'ASC')->get()
            ->pluck('price', 'timestamp')->toArray());
    }
}
