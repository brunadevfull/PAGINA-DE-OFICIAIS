<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DutyAssignment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamController extends Controller
{
    public function documents(Request $request): StreamedResponse
    {
        return response()->stream(function () {
            $this->initStream();
            $conn = $this->pgConnect();

            if (!$conn) {
                $this->send(['error' => 'DB connection failed']);
                return;
            }

            pg_query($conn, 'LISTEN documents_changed');

            $this->send([
                'type'      => 'snapshot',
                'documents' => Document::orderBy('upload_date', 'desc')->get(),
                'timestamp' => now()->toISOString(),
            ]);

            $socket = pg_socket($conn);
            $lastHeartbeat = time();

            while (!connection_aborted()) {
                $read = [$socket];
                $write = $except = null;
                $ready = stream_select($read, $write, $except, 1);

                if ($ready === false) {
                    break;
                }

                if ($ready > 0) {
                    pg_consume_input($conn);
                }

                $notify = pg_get_notify($conn, PGSQL_ASSOC);

                if ($notify) {
                    $this->send([
                        'type'      => 'update',
                        'documents' => Document::orderBy('upload_date', 'desc')->get(),
                        'timestamp' => now()->toISOString(),
                    ]);
                }

                if (time() - $lastHeartbeat >= 30) {
                    echo ": heartbeat\n\n";
                    $this->flush();
                    $lastHeartbeat = time();
                }
            }

            pg_close($conn);
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection'        => 'keep-alive',
        ]);
    }

    public function dutyOfficers(Request $request): StreamedResponse
    {
        return response()->stream(function () {
            $this->initStream();
            $conn = $this->pgConnect();

            if (!$conn) {
                $this->send(['error' => 'DB connection failed']);
                return;
            }

            pg_query($conn, 'LISTEN duty_assignments_changed');

            $this->send([
                'type'      => 'snapshot',
                'officers'  => DutyAssignment::orderByDesc('valid_from')->orderByDesc('updated_at')->first(),
                'timestamp' => now()->toISOString(),
            ]);

            $socket = pg_socket($conn);
            $lastHeartbeat = time();

            while (!connection_aborted()) {
                $read = [$socket];
                $write = $except = null;
                $ready = stream_select($read, $write, $except, 1);

                if ($ready === false) {
                    break;
                }

                if ($ready > 0) {
                    pg_consume_input($conn);
                }

                $notify = pg_get_notify($conn, PGSQL_ASSOC);

                if ($notify) {
                    $this->send([
                        'type'     => 'update',
                        'officers' => DutyAssignment::orderByDesc('valid_from')->orderByDesc('updated_at')->first(),
                        'timestamp' => now()->toISOString(),
                    ]);
                }

                if (time() - $lastHeartbeat >= 30) {
                    echo ": heartbeat\n\n";
                    $this->flush();
                    $lastHeartbeat = time();
                }
            }

            pg_close($conn);
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection'        => 'keep-alive',
        ]);
    }

    private function initStream(): void
    {
        set_time_limit(0);
        ignore_user_abort(false);

        // Clear any output buffers
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
    }

    private function send(array $data): void
    {
        echo "data: " . json_encode($data) . "\n\n";
        $this->flush();
    }

    private function flush(): void
    {
        if (ob_get_level() > 0) {
            ob_flush();
        }
        flush();
    }

    private function pgConnect(): mixed
    {
        $dsn = sprintf(
            'host=%s port=%s dbname=%s user=%s password=%s',
            config('database.connections.pgsql.host'),
            config('database.connections.pgsql.port'),
            config('database.connections.pgsql.database'),
            config('database.connections.pgsql.username'),
            config('database.connections.pgsql.password'),
        );

        // @ suprime warnings do pg_connect que incluiriam a DSN (com senha) no output
        $conn = @pg_connect($dsn);
        if ($conn === false) {
            \Log::error('SSE: falha ao conectar ao PostgreSQL');
            return null;
        }

        return $conn;
    }
}
