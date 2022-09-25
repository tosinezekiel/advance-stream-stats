<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Braintree\Subscription as BraintreeSubscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'ends_at',
    ];

    public function isActive() : bool
    {
        return is_null($this->ends_at);
    }

    public function isCancelled() : bool
    {
        return !is_null($this->ends_at);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cancel() : self
    {
        $subscription = $this->asBraintreeSubscription();

        BraintreeSubscription::update($subscription->id, [
            'numberOfBillingCycles' => $subscription->currentBillingCycle,
        ]);

        $this->ends_at = $subscription->billingPeriodEndDate;

        $this->save();

        return $this;
    }

    public function cancelNow() : self
    {
        $subscription = $this->asBraintreeSubscription();

        BraintreeSubscription::cancel($subscription->id);

        $this->markAsCancelled();

        return $this;
    }

    public function markAsCancelled() : void
    {
        $this->fill(['ends_at' => Carbon::now()])->save();
    }
}
