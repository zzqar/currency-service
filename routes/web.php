<?php

use App\Http\Controllers\ExchangeRates;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/currency-rates', [ExchangeRates::class, 'getCurrencyRates']);

