<?php
namespace App\Services;

use Braintree;
use Exception;
use App\Models\User;
use Braintree\Gateway;
use App\Models\Subscription;
use Braintree\Exception\NotFound as BraintreeNotFoundException;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionService{

    private $gateway;

    const PLAN_PRICE = 12;
    const YEARLY_DURATION = 12;
    const YEARLY_DISCOUNT = 57;

    public function __construct()
    {
        $this->gateway = BraintreeService::gateway();
    }

    public function findPlan(string $id) : Braintree\Plan
    {
        try {
            $plans = $this->gateway->plan()->all();
    
            foreach ($plans as $plan) {
                if ($plan->id === $id) {
                    return $plan;
                }
            }
        } catch (BraintreeNotFoundException $e){
            throw new Exception('Unable to find Braintree plan with ID: '.$e->getMessage());
        }
    }

    public function plans() : array
    {
        $plans = $this->gateway->plan()->all();

        return $plans;
    }

    public function subscribe(User $user, string $token, string $subscription, Braintree\Plan $plan, string $type = 'monthly') : Subscription
    {
        $instance = $user->newSubscription($subscription, $plan->id);
        return $instance->create($token, [], self::determinePlanOptions($plan, $type));
    }
    public function getSubscriptions() : Collection
    {
        $user = User::where('id', auth()->user()->id)->first();
        return $user->subscriptions();
    }

    public function createAsBraintreeSubscription(array $payload) : Braintree\Result\Successful
    {
        return $this->gateway->subscription()->create($payload);
    }

    public function cancel(Subscription $subscription) : Subscription
    {
        return $subscription->cancelNow();
    }

    public static function determinePlanOptions(Braintree\Plan $plan, string $type) : array
    {
        return match($type) {
            "yearly" => self::setPlanOptions($plan),
            "monthly" => []
        };
        return [];
    }

    private static function setPlanOptions(Braintree\Plan $plan) : array
    {
        return [
            "price" => self::calculateDiscount((int) $plan->price),
        ];
    }

    private static function calculateDiscount(int $price) : int 
    {
        return (int) ($price * self::YEARLY_DURATION) - self::YEARLY_DISCOUNT;
    }

}