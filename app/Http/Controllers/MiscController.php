<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MiscController extends Controller
{
    public function terms()
    {
        return view('misc.terms');
    }

    public function newPaymentToSlack()
    {
        $input = @file_get_contents("php://input");

        $payload = json_decode($input);

        Log::channel('slack')->info('New payment', ['data' => $payload]);
    }
}
