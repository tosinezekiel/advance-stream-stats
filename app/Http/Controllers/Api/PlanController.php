<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Illuminate\Http\Response;

class PlanController extends Controller
{
    public SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function index() : Response
    {
        $plans = $this->subscriptionService->plans();
        return response([
            'plans' => $plans, 
            'status' => true
        ], 200);
    }
}
