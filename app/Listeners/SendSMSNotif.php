<?php

namespace App\Listeners;

use App\Events\GroomingFinishNotif;
use App\ServiceTransaction;
use App\Pet;
use App\ServiceDetail;
use App\Service;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSMSNotif implements ShouldQueue
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
     * @param  \App\Events\GroomingFinishNotif $grooming_finish_notif
     * @return void
     */
    public function handle(GroomingFinishNotif $grooming_finish_notif)
    {
        $service_transaction_detail = $grooming_finish_notif->service_transaction_detail;
        $service_detail = ServiceDetail::find($service_transaction_detail->ServiceDetails_id, ['Services_id']);
        $service = Service::find($service_detail->Services_id);

        if(strcasecmp($service->name, "grooming") === 0 && $service_code->isFinished == 1) {
            $service_code = $service_transaction_detail->ServiceTransaction_id;
            $service_transaction = ServiceTransaction::find($service_code, ['Pets_id']);
            $pet = Pet::find($service_transaction->Pets_id, ['name']); 
            $sms_msg = "Grooming $pet->name dengan nomor layanan $service_code sudah selesai.";
            $this->sendSms($sms_msg);
        }
    }

    private function sendSms($msg) {

    }
}
