<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDutyOfficerRequest;
use App\Models\DutyAssignment;
use Illuminate\Http\JsonResponse;

class DutyOfficerController extends Controller
{
    public function index(): JsonResponse
    {
        $assignment = DutyAssignment::orderByDesc('valid_from')->orderByDesc('updated_at')->first();

        return response()->json([
            'success'   => true,
            'officers'  => $assignment,
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function update(StoreDutyOfficerRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['valid_from'] = $data['valid_from'] ?? now();
        $data['updated_at'] = now();

        $assignment = DutyAssignment::create($data);

        return response()->json([
            'success'   => true,
            'officers'  => $assignment,
            'message'   => 'Oficiais de serviço atualizados com sucesso',
            'timestamp' => now()->toISOString(),
        ]);
    }
}
