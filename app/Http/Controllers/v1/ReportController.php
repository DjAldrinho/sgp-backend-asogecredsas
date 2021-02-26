<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\CreditService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private $creditService;

    /**
     * CreditController constructor.
     * @param CreditService $creditService
     */
    public function __construct(CreditService $creditService)
    {
        $this->creditService = $creditService;
    }

    public function credits(Request $request)
    {
        $credits = $this->creditService->getCredits($request);
    }
}
