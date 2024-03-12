<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyLogRequest;
use App\Services\Company\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CompanyLogController extends Controller
{
    public function __construct(
        private CompanyService $companyService
    ) {}

    public function store(StoreCompanyLogRequest $request): JsonResponse
    {
        $this->companyService->createLog(
            $request->getAction(),
            $request->getPayload()
        );

        return response()->json([], Response::HTTP_CREATED);
    }
}
