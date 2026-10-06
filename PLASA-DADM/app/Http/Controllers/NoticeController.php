<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoticeRequest;
use App\Http\Requests\UpdateNoticeRequest;
use App\Models\Notice;
use Illuminate\Http\JsonResponse;

class NoticeController extends Controller
{
    public function index(): JsonResponse
    {
        $notices = Notice::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success'   => true,
            'notices'   => $notices,
            'count'     => $notices->count(),
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function store(StoreNoticeRequest $request): JsonResponse
    {
        $notice = Notice::create($request->validated());
        return response()->json([
            'success' => true,
            'notice'  => $notice,
            'message' => 'Notice created successfully',
        ]);
    }

    public function update(UpdateNoticeRequest $request, int $id): JsonResponse
    {
        $notice = Notice::findOrFail($id);
        $notice->update($request->validated());
        return response()->json([
            'success' => true,
            'notice'  => $notice->fresh(),
            'message' => 'Notice updated successfully',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();
        return response()->json([
            'success'   => true,
            'message'   => 'Notice deleted successfully',
            'deletedId' => $id,
        ]);
    }
}
