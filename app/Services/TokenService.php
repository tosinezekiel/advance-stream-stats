<?php
namespace App\Services;

class TokenService{

    private $gateway;

    public function __construct()
    {
        $this->gateway = BraintreeService::gateway();
    }

    public function generateToken() : string
    {
        return $this->gateway->clientToken()->generate();
    }

}