<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Services\CbrApiService;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class ExchangeRates extends Controller
{
    /**
     * @throws InvalidArgumentException
     */
    public function getCurrencyRates(): \Illuminate\Http\JsonResponse
    {
        if (Currency::hasCache()){
            return response()->json(Currency::getByCache());
        }

        $response = (new CbrApiService())->getExchangeRates();
        return response()->json($response);
    }
}
