<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Credit\CreditService;

class CreditController extends Controller
{
    protected CreditService $credits;

    public function __construct(CreditService $credits)
    {
        $this->credits = $credits;
    }

    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'balance' => $this->credits->getAvailableBalance($request->user()),
        ]);
    }
}
