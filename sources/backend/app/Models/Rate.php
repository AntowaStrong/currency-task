<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rate extends Model
{
    use HasFactory;

    /**
     * Api request interval.
     *
     * @var array
     */
    static private $interval = 10;

    /**
     * List of available currency codes.
     *
     * @var array
     */
    static private $currencies = [
        'USD', 'EUR', 'CZk'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'base',
        'rate'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rate' => 'array',
        'updated_at' => 'timestamp'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Returns list of available currencies.
     *
     * @return array List of currencies.
     */
    static public function getCurrencies (): array
    {
        return self::$currencies;
    }

    /**
     * Returns conversional rate.
     *
     * @param string $from From currency code.
     * @param string $to To currency code.
     * @return float (optional) Conversional rate.
     */
    static public function getRate (string $from, string $to): ?float
    {
        if (!in_array($from, self::getCurrencies()) || !in_array($to, self::getCurrencies())) {
            return null;
        }

        $rate = self::where('base', $from)->first();

        if (!$rate) {
          $rate = new self();

          $rate->base = $from;
        }

        if (!$rate->updated_at || time() - $rate->updated_at > self::$interval) {
            $response = Http::get('http://api.openrates.io/latest', [
                'base' => $from,
                // 'symbols' => self::getCurrencies() Fix url encoding.
            ]);

            if ($response->failed()) {
                return null;
            }

            $rate->rate = $response->json()['rates'];

            $rate->save();
        }

        return isset($rate->rate[$to]) ? round($rate->rate[$to], 3) : null;
    }
}
