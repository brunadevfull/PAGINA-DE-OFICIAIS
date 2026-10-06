<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    public function health(): JsonResponse
    {
        try {
            DB::select('SELECT 1');
            return response()->json([
                'status'    => 'ok',
                'timestamp' => now()->toISOString(),
                'message'   => 'Marinha do Brasil - Sistema funcionando corretamente',
            ]);
        } catch (\Throwable $e) {
            Log::error('Health check DB failure', ['message' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Banco de dados indisponível'], 503);
        }
    }

    public function publicStatus(Request $request): JsonResponse
    {
        return response()->json([
            'status'      => 'online',
            'version'     => '2.0-laravel',
            'clientIP'    => $request->ip(),
            'serverHost'  => $request->getHost(),
            'environment' => config('app.env'),
        ]);
    }

    public function status(): JsonResponse
    {
        $totalSize = 0;
        $pdfCount  = 0;

        foreach (['plasa', 'escala', 'cardapio', 'outros', 'bono'] as $type) {
            $files = Storage::disk('uploads')->files($type);
            foreach ($files as $file) {
                $totalSize += Storage::disk('uploads')->size($file);
                if (str_ends_with($file, '.pdf')) $pdfCount++;
            }
        }

        return response()->json([
            'server' => [
                'status'     => 'online',
                'version'    => '2.0-laravel',
                'timestamp'  => now()->toISOString(),
                'phpVersion' => PHP_VERSION,
                'platform'   => PHP_OS,
            ],
            'storage' => [
                'uploads' => [
                    'totalSize' => $totalSize,
                    'pdfCount'  => $pdfCount,
                ],
            ],
        ]);
    }

    public function temperature(): JsonResponse
    {
        $apiKey = config('services.openweather.api_key');
        $lat    = config('services.openweather.latitude');
        $lon    = config('services.openweather.longitude');

        if (!$apiKey) {
            return response()->json(['error' => 'API key não configurada'], 503);
        }

        try {
            $response = Http::timeout(5)->get('https://api.openweathermap.org/data/2.5/weather', [
                'lat'   => $lat,
                'lon'   => $lon,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang'  => 'pt_br',
            ]);

            if (!$response->ok()) {
                return response()->json(['error' => 'Falha ao obter temperatura'], 502);
            }

            $data = $response->json();
            return response()->json([
                'temperature' => $data['main']['temp'] ?? null,
                'feels_like'  => $data['main']['feels_like'] ?? null,
                'description' => $data['weather'][0]['description'] ?? null,
                'icon'        => $data['weather'][0]['icon'] ?? null,
                'city'        => $data['name'] ?? 'Rio de Janeiro',
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Falha ao obter temperatura'], 500);
        }
    }

    public function clearCache(): JsonResponse
    {
        Cache::flush();
        return response()->json(['success' => true, 'message' => 'Cache limpo com sucesso']);
    }

    public function logs(Request $request): JsonResponse
    {
        $lines = (int) $request->query('lines', 100);
        $logPath = storage_path('logs/laravel.log');

        if (!file_exists($logPath)) {
            return response()->json(['success' => true, 'logs' => []]);
        }

        $allLines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lastLines = array_slice($allLines, -min($lines, 500));

        return response()->json(['success' => true, 'logs' => $lastLines]);
    }

    public function systemInfo(): JsonResponse
    {
        return $this->status();
    }
}
