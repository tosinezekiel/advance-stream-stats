<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\TokenService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\SubscriptionService;
use App\Http\Requests\SubscriptionRequest;

class SubscriptionController extends Controller
{
    public SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService, TokenService $tokenService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->tokenService = $tokenService;
    }

    public function index() : Response
    {
        $subscriptions = $this->subscriptionService->getSubscriptions();
        return response([
            'subscriptions' => $subscriptions, 
            'status' => true
        ], 200);
    }

    public function clientToken() : Response
    {
        $token = $this->tokenService->generateToken();
        return response([
            'token' => $token, 
            'status' => true
        ], 200);
    }

    public function create(SubscriptionRequest $request) : Response
    {
        $data = $request->validated();
        
        $user = User::where('id', auth()->user()->id)->first();
        $plan = $this->subscriptionService->findPlan($data['planId']);

        $subscription = $this->subscriptionService->subscribe($user, $data['token'], 'default', $plan, $data['type']);

        return response([
            'message' => 'Subscription created successfully',
            'user' => new UserResource(auth()->user()), 
            'status' => true
        ], 200);
    }

    public function destroy(Subscription $subscription) : Response
    {
        $result = $this->subscriptionService->cancel($subscription);
        return response([
            'user' => new UserResource(auth()->user()), 
            'message' => "Subscription cancelled successfully.", 
            'status' => true
        ], 200);
    }
}
