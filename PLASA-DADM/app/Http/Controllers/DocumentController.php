<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Models\DocumentViewState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{

    public function index(): JsonResponse
    {
        $documents = Document::orderBy('upload_date', 'desc')->get();
        return response()->json($documents);
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['tags']) && is_array($data['tags'])) {
            $data['tags'] = array_values(array_unique($data['tags']));
        }

        $document = Document::create($data);
        return response()->json($document);
    }

    public function update(UpdateDocumentRequest $request, int $id): JsonResponse
    {
        $document = Document::findOrFail($id);
        $document->update($request->validated());
        return response()->json($document->fresh());
    }

    public function destroy(int $id): JsonResponse
    {
        $document = Document::findOrFail($id);

        if ($document->url) {
            $relativePath = ltrim(parse_url($document->url, PHP_URL_PATH) ?? '', '/');
            if (str_starts_with($relativePath, 'uploads/')) {
                $relativePath = substr($relativePath, strlen('uploads/'));
            }
            if (Storage::disk('uploads')->exists($relativePath)) {
                Storage::disk('uploads')->delete($relativePath);
            }
        }

        $document->delete();
        return response()->json(['success' => true]);
    }

    public function getViewState(): JsonResponse
    {
        $states = DocumentViewState::all()->keyBy('document_id');
        $snapshot = [];
        foreach ($states as $docId => $state) {
            $snapshot[$docId] = [
                'zoom'       => $state->zoom,
                'scrollTop'  => $state->scroll_top,
                'scrollLeft' => $state->scroll_left,
            ];
        }
        return response()->json([
            'success'    => true,
            'viewStates' => $snapshot,
            'timestamp'  => now()->toISOString(),
        ]);
    }

    public function saveViewState(Request $request): JsonResponse
    {
        $data = $request->validate([
            'documentId' => 'required|string',
            'zoom'       => 'required|numeric',
            'scrollTop'  => 'required|numeric',
            'scrollLeft' => 'required|numeric',
        ]);

        $state = DocumentViewState::updateOrCreate(
            ['document_id' => $data['documentId']],
            [
                'zoom'        => $data['zoom'],
                'scroll_top'  => $data['scrollTop'],
                'scroll_left' => $data['scrollLeft'],
                'updated_at'  => now(),
            ]
        );

        return response()->json(['success' => true, 'viewState' => $state]);
    }
}
