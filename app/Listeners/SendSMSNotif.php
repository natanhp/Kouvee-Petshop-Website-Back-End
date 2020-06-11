<?php

namespace App\Listeners;

use App\Events\GroomingFinishNotif;
use App\ServiceTransaction;
use App\Pet;
use App\PetType;
use App\PetSize;
use App\Customer;
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
        $service_detail = ServiceDetail::find($service_transaction_detail->ServiceDetails_id, ['Services_id', 'PetTypes_id', 'PetSizes_id']);
        $service = Service::find($service_detail->Services_id);
        if(preg_match("/grooming/", strtolower($service->serviceName)) === 1 && $service_transaction_detail->isFinished == 1) {
            $service_code = $service_transaction_detail->ServiceTransaction_id;
            $service_transaction = ServiceTransaction::find($service_code, ['Pets_id']);
            $pet = Pet::find($service_transaction->Pets_id, ['name', 'Customers_id']);
            $type = PetType::find($service_detail->PetTypes_id, ['type'])->type;
            $size = PetSize::find($service_detail->PetSizes_id, ['size'])->size;
            $phone_num = Customer::find($pet->Customers_id, ['phoneNumber'])->phoneNumber;
            $sms_msg = "Nomor Layanan: $service_code \nLayanan: $service->serviceName $type $size \nNama Hewan: $pet->name \nKami infokan bahwa layanan tersebut sudah selesai, terimakasih.";
            $this->sendSms($sms_msg, $phone_num);
        }
    }

    private function sendSms($msg, $phone_num) {
        date_default_timezone_set(env('APP_TIMEZONE'));

        $time = time();
        $deviceID = env('SMS_DEVICE_ID');
        $secret = env('SMS_SECRET');
        //you can hash to md5 to protect your secret, or you just send the secret
        $secret = md5($secret.$time);
        // USING GET
        file_get_contents("https://sms.ibnux.net/?to=".urlencode($phone_num)."&text=".urlencode($msg)."&secret=$secret&time=$time&deviceID=".urlencode($deviceID));
    }
}
