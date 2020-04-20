<?php

namespace App\Listeners;

use App\Events\ProductMinReached;
use App\FCMModel;
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
        $product = $product_min_reached->product;
        $product_quantity = $product->productQuantity;
        $product_min_quantity = $product->minimumQty;
        $product_name = $product->productName;
        $product_metric = $product->meassurement;

        if($product_quantity < $product_min_quantity) {
            $this->sendMultipleDevices($product_name, $product_quantity, $product_metric);
        }
    }

    private function sendMultipleDevices($product_name, $product_stock, $product_metric) {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setCollapseKey('a_collapse_key');

        $notificationBuilder = new PayloadNotificationBuilder("$product_name Hampir Habis");
        $notificationBuilder->setBody("Stok produk sekarang $product_stock $product_metric")
                            ->setSound('default');

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        // You must change it to get your tokens
        $tokens = FCMModel::pluck('token')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        // return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $downstreamResponse->tokensToModify();

        // return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:error) - in production you should remove from your database the tokens present in this array
        $downstreamResponse->tokensWithError();
    }
}
