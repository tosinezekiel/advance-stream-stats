<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\TokenService;
use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use App\Http\Requests\SubscriptionRequest;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    public SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService, TokenService $tokenService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->tokenService = $tokenService;
    }

    public function index() 
    {
        $plans = $this->subscriptionService->plans();
        return response([
            'plans' => $plans, 
            'status' => true
        ], 200);
    }

    public function clientToken(Request $request) 
    {
        $token = $this->tokenService->generateToken();
        return response([
            'token' => $token, 
            'status' => true
        ], 200);
    }

    public function create(SubscriptionRequest $request) 
    {
        $data = $request->validated();
        
        $user = User::where('id', auth()->user()->id)->first();
        $plan = $this->subscriptionService->findPlan($data['planId']);

        $subscription = $this->subscriptionService->subscribe($user, $data['token'], 'default', $plan, $data['type']);

        return response([
            'message' => 'Subscription created successfully',
            'subscription' => $subscription, 
            'status' => true
        ], 200);
    }

    public function destroy(Request $request) 
    {
        $token = $this->subscriptionService->cancel();
        return response([
            'message' => "Subscription cancelled successfully.", 
            'status' => true
        ], 200);
    }
}
