<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\SiteSetting;
use Illuminate\View\View;

class DonateController extends Controller
{
    /** Show the main donation page with campaigns and payment methods. */
    public function index(): View
    {
        return view('donate.index', [
            'settings' => SiteSetting::current(),
            'paymentMethods' => PaymentMethod::with('gateway')->active()->get(),
        ]);
    }
}
