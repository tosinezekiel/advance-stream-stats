<?php
namespace App\Services;

use Braintree;
use Braintree\Gateway;

class BraintreeService{

    public static function gateway() : Braintree\Gateway
    {
        return new Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId'  => config('services.braintree.merchant_id'),
            'publicKey'   => config('services.braintree.public_key'),
            'privateKey'  => config('services.braintree.private_key')
        ]);
    }
}