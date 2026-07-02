<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    /** Get all currency rates as a JSON map (currency => rate). */
    public function rates(): JsonResponse
    {
        $rates = CurrencyRate::all()->pluck('rate', 'currency');
        $rates['USD'] = 1;
        return response()->json($rates);
    }
}
