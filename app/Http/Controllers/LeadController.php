<?php

namespace App\Http\Controllers;

use App\Services\LeadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LeadRequest;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function store(LeadRequest $request, LeadService $leadService): JsonResponse
    {
        $validated = $request->validated();

        $lead = $leadService->register($validated);

        return response()->json([
            'message' => __('Đăng ký nhận ưu đãi thành công.'),
            'lead' => $lead,
        ], 201);
    }
}
