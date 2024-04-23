<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class Currency extends Model
{
    use HasFactory;



    protected $table = 'currencies';

    protected $fillable = [
        'currency_id',
        'date',
        'num_code',
        'char_code',
        'nominal',
        'name',
        'value',
        'vunit_rate',
    ];

    // Отключение полей created_at и updated_at
    public $timestamps = false;


    public static function hasCache(): bool
    {
        return Cache::store('memcached')->has('currencies_' . date('Y-m-d'));
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function getByCache()
    {
        return Cache::store('memcached')->get('currencies_' . date('Y-m-d'));
    }

    public static function setByCache(array $arrCurrency)
    {
        Cache::store('memcached')->put(
            'currencies_' . date('Y-m-d'),
            $arrCurrency,
            60 * 60 * 24
        );
    }

    public static function createByArray($data)
    {
        $return = array_map(
            fn($val) => Currency::create([
                'currency_id' => $val['@attributes']['ID'],
                'date' =>  date('Y-m-d'),
                'num_code' => $val['NumCode'],
                'char_code' => $val['CharCode'],
                'nominal' => $val['Nominal'],
                'name' => $val['Name'],
                'value' => $val['Value'],
                'vunit_rate' => $val['VunitRate'],
            ]),
           $data
        );

        self::setByCache($return);
        return $return;
    }

}
