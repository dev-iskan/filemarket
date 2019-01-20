<?php

namespace App\Jobs\Checkout;

use App\Events\Checkout\SaleWasCreated;
use App\File;
use App\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateSale
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file;
    public $email;

    public function __construct(File $file, $email)
    {
        // pass file that has been purchased and email of user
        $this->file = $file;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sale = new Sale;
        $sale->fill([
            'identifier' => uniqid(true),
            'buyer_email' => $this->email,
            'sale_price' => $this->file->price,
            'sale_commission' => $this->file->calculateCommission()
        ]);

        $sale->file()->associate($this->file);
        $sale->user()->associate($this->file->user);
        $sale->save();

        //fire events
        event(new SaleWasCreated($sale));
    }
}
