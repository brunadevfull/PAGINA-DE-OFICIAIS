<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Models\Document;
use App\Services\ClassificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    private const MAX_FILE_SIZE = 100 * 1024 * 1024; // 100 MB

    private static array $allowedTypes = ['plasa', 'escala', 'cardapio', 'outros', 'bono', 'plasa-pages', 'escala-cache'];
    // only types that have Document rows — plasa-pages images do not
    private static array $documentTypes = ['plasa', 'escala', 'cardapio', 'outros', 'bono'];

    public function __construct(private readonly ClassificationService $classifier) {}

    public function uploadPdf(UploadFileRequest $request): JsonResponse
    {
        $file  = $request->file('pdf');
        $title = $request->input('title');
        $type  = $request->input('type');
        $unit  = $request->input('unit');

        $safeBase = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = $safeBase . '-' . time() . '.pdf';

        $path = Storage::disk('uploads')->putFileAs($type, $file, $filename);
        $url  = '/uploads/' . $path;

        $classification = $this->classifier->classify($file->getClientOriginalName(), $title, $type);

        $document = Document::create([
            'title'       => $title,
            'url'         => $url,
            'type'        => $classification['type'],
            'category'    => $classification['category'],
            'unit'        => $classification['unit'] ?? $unit,
            'tags'        => $classification['tags'],
            'active'      => true,
            'upload_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'filename'       => $filename,
                'originalname'   => $file->getClientOriginalName(),
                'size'           => $file->getSize(),
                'url'            => $url,
                'title'          => $title,
                'type'           => $classification['type'],
                'category'       => $classification['category'],
                'unit'           => $classification['unit'] ?? $unit,
                'tags'           => $classification['tags'],
                'classification' => $classification,
                'document'       => $document,
            ],
        ]);
    }

    public function serve(Request $request, string $type, string $file): \Symfony\Component\HttpFoundation\Response
    {
        if (!in_array($type, self::$allowedTypes, true)) {
            abort(404);
        }

        // Allow one subdirectory level (e.g. plasa-pages/{docId}/page-1.jpg)
        $path = "$type/" . $this->sanitizePath($file);

        if (!Storage::disk('uploads')->exists($path)) {
            abort(404);
        }

        return Storage::disk('uploads')->response($path);
    }

    public function delete(Request $request, string $type, string $file): JsonResponse
    {
        if (!in_array($type, self::$allowedTypes, true)) {
            return response()->json(['success' => false, 'message' => 'Tipo inválido'], 400);
        }

        $safeFile = $this->sanitizePath($file);
        $path = "$type/$safeFile";

        if (!Storage::disk('uploads')->exists($path)) {
            return response()->json(['success' => false, 'message' => 'Arquivo não encontrado'], 404);
        }

        Storage::disk('uploads')->delete($path);

        Document::where('url', 'like', "%/$type/$safeFile")->delete();

        return response()->json(['success' => true, 'message' => 'Arquivo removido']);
    }

    public function list(Request $request, string $type): JsonResponse
    {
        if (!in_array($type, self::$allowedTypes, true)) {
            return response()->json(['success' => false, 'message' => 'Tipo inválido'], 400);
        }

        $files = Storage::disk('uploads')->files($type);
        $result = array_map(fn($f) => [
            'name' => basename($f),
            'url'  => '/uploads/' . $f,
            'size' => Storage::disk('uploads')->size($f),
        ], $files);

        return response()->json(['success' => true, 'files' => $result]);
    }

    public function config(): JsonResponse
    {
        return response()->json([
            'maxFileSize' => self::MAX_FILE_SIZE,
            'allowedTypes' => self::$allowedTypes,
            'allowedMimes' => ['application/pdf'],
        ]);
    }

    public function cleanup(): JsonResponse
    {
        $deleted = 0;
        foreach (self::$documentTypes as $type) {
            $files = Storage::disk('uploads')->files($type);
            if (empty($files)) {
                continue;
            }
            $urls = array_map(fn($f) => '/uploads/' . $f, $files);
            $known = Document::whereIn('url', $urls)->pluck('url')->flip()->all();
            foreach ($files as $file) {
                if (!isset($known['/uploads/' . $file])) {
                    Storage::disk('uploads')->delete($file);
                    $deleted++;
                }
            }
        }

        return response()->json(['success' => true, 'deletedOrphans' => $deleted]);
    }

    public function updateMeta(Request $request, string $type, string $file): JsonResponse
    {
        if (!in_array($type, self::$allowedTypes, true)) {
            return response()->json(['success' => false, 'message' => 'Tipo inválido'], 400);
        }

        $data = $request->validate([
            'title'    => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:100',
            'unit'     => 'sometimes|nullable|string|max:100',
            'active'   => 'sometimes|boolean',
            'tags'     => 'sometimes|array',
            'tags.*'   => 'string|max:100',
        ]);

        $url = "/uploads/$type/" . basename($file);
        $document = Document::where('url', $url)->firstOrFail();
        $document->update($data);

        return response()->json(['success' => true, 'document' => $document->fresh()]);
    }

    public function deletePdf(Request $request, string $filepath): JsonResponse
    {
        $safePath = $this->sanitizePath($filepath);

        if (empty($safePath)) {
            return response()->json(['success' => false, 'message' => 'Caminho inválido'], 400);
        }

        if (!Storage::disk('uploads')->exists($safePath)) {
            return response()->json(['success' => false, 'message' => 'Arquivo não encontrado'], 404);
        }

        Storage::disk('uploads')->delete($safePath);
        Document::where('url', '/uploads/' . $safePath)->delete();

        return response()->json(['success' => true]);
    }

    public function listPdfs(Request $request): JsonResponse
    {
        $files = [];
        foreach (self::$documentTypes as $type) {
            foreach (Storage::disk('uploads')->files($type) as $file) {
                if (!str_ends_with($file, '.pdf')) {
                    continue;
                }
                $files[] = [
                    'filename'   => basename($file),
                    'type'       => $type,
                    'url'        => '/uploads/' . $file,
                    'size'       => Storage::disk('uploads')->size($file),
                    'uploadedAt' => Storage::disk('uploads')->lastModified($file),
                ];
            }
        }
        return response()->json(['success' => true, 'files' => $files]);
    }

    public function checkPlasaPages(Request $request): JsonResponse
    {
        $data = $request->validate([
            'documentId' => 'required|string|max:50',
            'pageCount'  => 'required|integer|min:1|max:500',
        ]);

        $documentId = preg_replace('/[^a-zA-Z0-9_-]/', '', $data['documentId']);
        $pageCount  = (int) $data['pageCount'];
        $pages      = [];

        for ($i = 1; $i <= $pageCount; $i++) {
            foreach (['png', 'jpg', 'jpeg'] as $ext) {
                $path = "plasa-pages/$documentId/page-$i.$ext";
                if (Storage::disk('uploads')->exists($path)) {
                    $pages[] = ['page' => $i, 'url' => "/uploads/$path"];
                    break;
                }
            }
        }

        return response()->json([
            'success'       => true,
            'pages'         => $pages,
            'allPagesReady' => count($pages) === $pageCount,
        ]);
    }

    public function cachePlasaPage(Request $request): JsonResponse
    {
        $request->validate([
            'file'       => 'required|file|mimes:jpeg,png,jpg|max:10240',
            'pageNumber' => 'required|integer|min:1|max:500',
            'documentId' => 'nullable|string|max:50',
        ]);

        $documentId = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->input('documentId', 'default'));
        $pageNumber = $request->input('pageNumber');
        $ext        = $request->file('file')->extension();
        $filename   = "page-{$pageNumber}.{$ext}";
        $dir        = "plasa-pages/$documentId";

        Storage::disk('uploads')->putFileAs($dir, $request->file('file'), $filename);

        return response()->json([
            'success' => true,
            'url'     => "/uploads/{$dir}/{$filename}",
            'page'    => $pageNumber,
        ]);
    }

    public function checkEscalaCache(Request $request, string $escalId): JsonResponse
    {
        $safeId = preg_replace('/[^a-zA-Z0-9_-]/', '', $escalId);

        foreach (['png', 'jpg', 'jpeg'] as $ext) {
            $path = "escala-cache/$safeId.$ext";
            if (Storage::disk('uploads')->exists($path)) {
                return response()->json(['exists' => true, 'url' => "/uploads/$path"]);
            }
        }

        return response()->json(['exists' => false]);
    }

    public function saveEscalaCache(Request $request): JsonResponse
    {
        $data = $request->validate([
            'escalId'   => 'required|string|max:50',
            'imageData' => 'required|string',
            'format'    => 'sometimes|string|in:png,jpg,jpeg',
        ]);

        $safeId    = preg_replace('/[^a-zA-Z0-9_-]/', '', $data['escalId']);
        $ext       = $data['format'] ?? 'png';
        $imageData = $data['imageData'];

        if (str_contains($imageData, ',')) {
            $imageData = explode(',', $imageData, 2)[1];
        }

        $decoded = base64_decode($imageData, true);
        if ($decoded === false) {
            return response()->json(['success' => false, 'message' => 'Dados de imagem inválidos'], 400);
        }

        $filename = "$safeId.$ext";
        Storage::disk('uploads')->put("escala-cache/$filename", $decoded);

        return response()->json(['success' => true, 'url' => "/uploads/escala-cache/$filename"]);
    }

    public function proxyPdf(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $url  = $request->query('url', '');
        $path = parse_url($url, PHP_URL_PATH) ?? '';

        if (!str_starts_with($path, '/uploads/')) {
            abort(403);
        }

        $relative = substr($path, strlen('/uploads/'));
        $safePath = $this->sanitizePath($relative);

        if (!Storage::disk('uploads')->exists($safePath)) {
            abort(404);
        }

        return Storage::disk('uploads')->response($safePath);
    }

    public function clearImageCache(Request $request): JsonResponse
    {
        $deleted = 0;
        foreach (['plasa-pages', 'escala-cache'] as $dir) {
            foreach (Storage::disk('uploads')->allFiles($dir) as $file) {
                Storage::disk('uploads')->delete($file);
                $deleted++;
            }
        }
        return response()->json(['success' => true, 'deletedFiles' => $deleted]);
    }

    private function sanitizePath(string $raw): string
    {
        $parts = array_values(array_filter(
            array_map(fn($s) => preg_replace('/[^a-zA-Z0-9._-]/', '', $s), explode('/', $raw))
        ));
        return implode('/', $parts);
    }

    public function uploadPlasaPage(Request $request): JsonResponse
    {
        $request->validate([
            'file'       => 'required|file|mimes:jpeg,png,jpg|max:10240',
            'pageNumber' => 'required|integer|min:1',
            'documentId' => 'nullable|string|max:50',
        ]);

        $documentId = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->input('documentId', 'default'));
        $pageNumber = $request->input('pageNumber');
        $ext = $request->file('file')->extension();
        $filename = "page-{$pageNumber}.{$ext}";
        $dir = "plasa-pages/$documentId";

        Storage::disk('uploads')->putFileAs($dir, $request->file('file'), $filename);

        return response()->json([
            'success'  => true,
            'url'      => "/uploads/{$dir}/{$filename}",
            'page'     => $pageNumber,
        ]);
    }
}
