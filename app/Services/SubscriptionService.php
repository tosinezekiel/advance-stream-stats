<?php
namespace App\Services;

use Exception;
use App\Models\User;
use Braintree;
use Braintree\Gateway;
use Braintree\Exception\NotFound as BraintreeNotFoundException;

class SubscriptionService{

    private $gateway;

    const PLAN_PRICE = 12;
    const YEARLY_DURATION = 12;
    const YEARLY_DISCOUNT = 57;

    public function __construct()
    {
        $this->gateway = BraintreeService::gateway();
    }

    /**
     * Get the Braintree plan that has the given ID.
     *
     * @param  string  $id
     * @throws \Exception
     */
    public function findPlan($id)
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

    public function plans()
    {
        $plans = $this->gateway->plan()->all();

        return $plans;
    }

    public function subscribe(User $user, string $token, string $subscription, Braintree\Plan $plan, string $type = 'monthly'){
        $instance = $user->newSubscription($subscription, $plan->id);
        return $instance->create($token, [], self::determinePlanOptions($plan, $type));
    }

    public function createAsBraintreeSubscription(array $payload) : Braintree\Result\Successful
    {
        return $this->gateway->subscription()->create($payload);
    }

    public function cancel(){
        
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
            "billingFrequency" => self::YEARLY_DURATION
        ];
    }

    private static function calculateDiscount(int $price) : int 
    {
        return (int) ($price * self::YEARLY_DURATION) - self::YEARLY_DISCOUNT;
    }

}