<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use App\Services\BraintreeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Braintree\Exception\NotFound as BraintreeNotFoundException;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'ends_at',
        'created_at'
    ];

    protected $casts = [
        'created_at'  => 'date:Y-m-d',
        'ends_at' => 'datetime:Y-m-d H:i:a',
    ];

    public function braintreeGateway() : \Braintree\Gateway
    {
        return BraintreeService::gateway();
    }

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


    public function cancelNow() : self
    {
        $subscription = $this->asBraintreeSubscription();

        $this->braintreeGateway()->subscription()->cancel($subscription->id);

        $this->markAsCancelled();

        return $this;
    }

    public function asBraintreeSubscription() 
    {
        try{
            return $this->braintreeGateway()->subscription()->find($this->braintree_id);
        }catch(BraintreeNotFoundException $e){
            throw new Exception('Unable to find Braintree subscription: '.$e->getMessage());
        }
    }

    public function markAsCancelled() : void
    {
        $this->fill([
            'ends_at' => Carbon::now(),
            'status' => 'cancelled'
        ])->save();
    }
}
