<?php

namespace App\Events;

use App\ServiceTransactionDetail;
use Illuminate\Queue\SerializesModels;

class GroomingFinishNotif extends Event
{
    use SerializesModels;

    public $service_transaction_detail;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ServiceTransactionDetail $service_transaction_detail)
    {
        $this->service_transaction_detail = $service_transaction_detail;
    }
}
