<?php

namespace App\Listeners\Checkout;

use App\Events\Checkout\SaleWasCreated;
use App\Mail\Checkout\SaleConfirmation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToBuyer
{


    /**
     * Handle the event.
     *
     * @param  SaleWasCreated  $event
     * @return void
     */
    public function handle(SaleWasCreated $event)
    {
        // send email to buyer
        Mail::to($event->sale->buyer_email)->send(new SaleConfirmation($event->sale));
    }
}
