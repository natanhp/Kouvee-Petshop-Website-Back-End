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
                            ->setSound('default')
                            ->setChannelId('min_qty_warning');

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        // You must change it to get your tokens
        $tokens = FCMModel::pluck('token')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification);
    }
}
