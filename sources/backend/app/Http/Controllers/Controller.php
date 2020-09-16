<?php

namespace App\Http\Controllers;

use App\Models\Rate;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function quote (Request $request) 
    {
        $validation = Validator::make($request->all(), [
            'amount' => 'required|integer|min:0',
            'fromCurrencyCode' => [
                'required',
                Rule::in(Rate::getCurrencies()),
            ],
            'toCurrencyCode' => [
                'required',
                Rule::in(Rate::getCurrencies()),
            ]
        ]);

        if ($validation->fails()) {
            return response()->json([
                'error' => $validation->messages()
            ], 400);
        }

        $amount = $request->get('amount');
        $from = $request->get('fromCurrencyCode');
        $to = $request->get('toCurrencyCode');

        $rate = Rate::getRate($from, $to);

        if (!$rate) {
            return response()->json([
                'error' => __('api.error')
            ], 500);
        }

        return response()->json([
            'exchangeRate' => $rate,
            'currencyCode' => $to,
            'amount' => round($rate * $amount),
        ]);
    }
}
