<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMilitaryPersonnelRequest;
use App\Http\Requests\UpdateMilitaryPersonnelRequest;
use App\Models\MilitaryPersonnel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MilitaryPersonnelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $type = $request->query('type');
        $query = MilitaryPersonnel::orderBy('name');

        if ($type && in_array($type, ['officer', 'master'])) {
            $query->where('type', $type);
        }

        $personnel = $query->get();
        return response()->json(['success' => true, 'data' => $personnel]);
    }

    public function store(StoreMilitaryPersonnelRequest $request): JsonResponse
    {
        $personnel = MilitaryPersonnel::create($request->validated());
        return response()->json(['success' => true, 'data' => $personnel]);
    }

    public function update(UpdateMilitaryPersonnelRequest $request, int $id): JsonResponse
    {
        $personnel = MilitaryPersonnel::findOrFail($id);
        $personnel->update($request->validated());
        return response()->json(['success' => true, 'data' => $personnel->fresh()]);
    }

    public function destroy(int $id): JsonResponse
    {
        $personnel = MilitaryPersonnel::findOrFail($id);
        $personnel->delete();
        return response()->json(['success' => true, 'message' => 'Military personnel deleted successfully']);
    }
}
