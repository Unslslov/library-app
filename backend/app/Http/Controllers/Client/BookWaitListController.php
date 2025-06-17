<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookWaitlist\StoreRequest;
use App\Models\BookWaitlist;
use App\Services\BookWaitlistService;
use Illuminate\Http\Request;

class BookWaitListController extends Controller
{
    public function __construct(private BookWaitlistService $waitlistService)
    {
    }
    public function store(StoreRequest $request)
    {
        $result = $this->waitlistService->addToWaitlist($request->validated());

        return response()->json([
            'message' => $result['message']
        ], $result['code']);
    }
}
