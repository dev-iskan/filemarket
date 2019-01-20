<?php

namespace App\Http\Controllers\Checkout;

use App\File;
use App\Http\Requests\Checkout\FreeCheckoutRequest;
use App\Http\Controllers\Controller;
use App\Jobs\Checkout\CreateSale;

class CheckoutController extends Controller
{
    public function free (FreeCheckoutRequest $request, File $file) {
        if(!$file->isFree()) {
            return back();
        }

        // Create Job that triggers sending email and sales and etc.
        dispatch(new CreateSale($file, $request->email));

        return back()->withSuccess('We have emailed your download link to you.');
    }
}
