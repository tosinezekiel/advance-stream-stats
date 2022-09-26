<?php

namespace App\Traits;

use Exception;
use App\Models\Subscription;
use Braintree\Exception\NotFound as BraintreeNotFoundException;
use App\Services\BraintreeService;
use Braintree\PayPalAccount;
use App\Builders\SubscriptionBuilder;
use App\Services\SubscriptionService;
use Braintree;

trait Billable{

    public function braintreeGateway() : Braintree\Gateway
    {
        return BraintreeService::gateway();
    }

    public function newSubscription(string $subscription, string $plan): SubscriptionBuilder
    {
        $subscriptionService = new SubscriptionService();
        return new SubscriptionBuilder($this, $subscription, $plan, $subscriptionService);
    }
    
    public function paymentMethod() 
    {
        $customer = $this->asBraintreeCustomer();

        foreach ($customer->paymentMethods as $paymentMethod) {
            if ($paymentMethod->isDefault()) {
                return $paymentMethod;
            }
        }
    }
    

    public function updateCard(string $token, array $options = []) : void
    {
        $customer = $this->asBraintreeCustomer();

        $response = $this->braintreeGateway()->paymentMethod()->create(
            array_replace_recursive([
                'customerId' => $customer->id,
                'paymentMethodNonce' => $token,
                'options' => [
                    'makeDefault' => true,
                    'verifyCard' => true,
                ],
            ], $options)
        );

        if (! $response->success) {
            throw new Exception('Braintree was unable to create a payment method: '.$response->message);
        }

        $paypalAccount = $response->paymentMethod instanceof PaypalAccount;

        $this->forceFill([
            'paypal_email' => $paypalAccount ? $response->paymentMethod->email : null,
            'card_brand' => $paypalAccount ? null : $response->paymentMethod->cardType,
            'card_last_four' => $paypalAccount ? null : $response->paymentMethod->last4,
        ])->save();

        $this->updateSubscriptionsToPaymentMethod(
            $response->paymentMethod->token
        );
    }

    protected function updateSubscriptionsToPaymentMethod(string $token) : void
    {
        foreach ($this->subscriptions as $subscription) {
            if ($subscription->active()) {
                $this->braintreeGateway()->customer()->update($subscription->braintree_id, [
                    'paymentMethodToken' => $token,
                ]);
            }
        }
    }


    public function createAsBraintreeCustomer($token, array $options = []) : Braintree\Customer
    {
        $response = $this->braintreeGateway()->customer()->create(
            array_replace_recursive([
                'firstName' => $this->first_name,
                'lastName' => $this->last_name,
                'email' => $this->email,
                'paymentMethodNonce' => $token,
                'creditCard' => [
                    'options' => [
                        'verifyCard' => true,
                    ],
                ],
            ], $options)
        );

        if (! $response->success) {
            throw new Exception('Unable to create Braintree customer: '.$response->message);
        }

        $this->braintree_id = $response->customer->id;

        $paymentMethod = $this->paymentMethod();

        $paypalAccount = $paymentMethod instanceof PayPalAccount;

        $this->fill([
            'braintree_id' => $response->customer->id,
            'paypal_email' => $paypalAccount ? $paymentMethod->email : null,
            'card_brand' => ! $paypalAccount ? $paymentMethod->cardType : null,
            'card_last_four' => ! $paypalAccount ? $paymentMethod->last4 : null,
        ])->save();

        return $response->customer;
    }

    public function asBraintreeCustomer()
    {
        try{

            return $this->braintreeGateway()->customer()->find($this->braintree_id);

        }catch(BraintreeNotFoundException $e){
            throw new Exception('Unable to find Braintree customer: '.$e->getMessage());
        }
    }

    public function subscription(string $subscription = 'default') : ?Subscription
    {
        return $this->subscriptions->where('status', 'Active')->sortByDesc(function ($value) {
            return $value->created_at->getTimestamp();
        })->first(function ($value) use ($subscription) {
            return $value->name === $subscription;
        });
    }

    public function hasBraintreeId()
    {
        return ! is_null($this->braintree_id);
    }

    public function subscribed(string $subscription = 'default', ?string $plan = null) : bool
    {
        $subscription = $this->subscription($subscription);

        if (is_null($subscription)) {
            return false;
        }

        if (is_null($plan)) {
            return $subscription->isActive();
        }

        return $subscription->isActive() && $subscription->braintree_plan === $plan;
    }
}
