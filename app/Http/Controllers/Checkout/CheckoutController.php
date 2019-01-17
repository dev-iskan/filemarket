<?php

namespace App\Http\Controllers\Checkout;

use App\File;
use App\Http\Requests\Checkout\FreeCheckoutRequest;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function free (FreeCheckoutRequest $request, File $file) {
        if(!$file->isFree()) {
            return back();
        }

        return back()->withSuccess('We have emailed your download link to you.');
    }
}
