<?php

namespace App\Builders;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Braintree\Customer;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Braintree\Exception\NotFound as BraintreeNotFoundException;

class SubscriptionBuilder
{
    /**
     * The model that is subscribing.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected ? User $owner;

    /**
     * The name of the subscription.
     *
     * @var string
     */
    protected ?string $name;

    /**
     * The name of the plan being subscribed to.
     *
     * @var string
     */
    protected ?string $plan;

    public SubscriptionService $subscriptionService;

    public function __construct(User $owner, string $name, string $plan, SubscriptionService $subscriptionService)
    {
        $this->name = $name;
        $this->plan = $plan;
        $this->owner = $owner;
        $this->subscriptionService = $subscriptionService;
    }

    public function create($token = null, array $customerOptions = [], array $subscriptionOptions = []): Subscription
    {
        $payload = $this->getSubscriptionPayload(  
            $this->getBraintreeCustomer($token, $customerOptions), $subscriptionOptions
        );

        $response = $this->subscriptionService->createAsBraintreeSubscription($payload);

        if (! $response->success) {
            throw new Exception('Braintree failed to create subscription: '.$response->message);
        }

        return $this->owner->subscriptions()->create([
            'name' => $this->name,
            'braintree_id'   => $response->subscription->id,
            'braintree_plan' => $this->plan,
            'ends_at' => null,
            'status' => $response->subscription->status
        ]);
    }


    protected function getSubscriptionPayload($customer, array $options = []) : array
    {
        try{
            $plan = $this->subscriptionService->findPlan($this->plan);

            return array_merge([
                'planId' => $this->plan,
                'price' => number_format($plan->price, 2, '.', ''),
                'paymentMethodToken' => $this->owner->paymentMethod()->token,
            ], $options);
        }catch(BraintreeNotFoundException $e){
            throw new Exception('Unable to find Braintree customer: '.$e->getMessage());
        }
    }

    protected function getBraintreeCustomer($token = null, array $options = [])
    {
        if (! $this->owner->braintree_id) {
            $customer = $this->owner->createAsBraintreeCustomer($token, $options);
        } else {
            $customer = $this->owner->asBraintreeCustomer();

            if ($token) {
                $this->owner->updateCard($token);
            }
        }

        return $customer;
    }
}
