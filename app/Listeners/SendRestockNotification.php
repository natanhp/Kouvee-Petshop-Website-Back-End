<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class SendRestockNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ProductMinReached $product_min_reached
     * @return void
     */
    public function handle(ProductMinReached $product_min_reached)
    {
        $product_quantity = $product_min_reached->product->productQuantity;
        $product_min_quantity = $product_min_reached->product->minimumQty;

        if($product_quantity < $product_min_quantity) {
        // TODO = Send fcm downstream message
        }
    }
}
