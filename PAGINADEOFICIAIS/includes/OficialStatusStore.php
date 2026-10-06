<?php

/**
 * Status temporário (bordo/terra) dos oficiais, guardado em arquivo.
 * Não persiste no banco por decisão de negócio; o arquivo pode ser perdido num restart.
 * Leitura e escrita acontecem sob o mesmo flock, então toggles simultâneos não se sobrescrevem.
 */
class OficialStatusStore
{
    const VALID_STATUSES = ['bordo', 'terra'];

    public static function path(): string
    {
        return sys_get_temp_dir() . '/oficiais_status.json';
    }

    public static function isValidStatus($status): bool
    {
        return is_string($status) && in_array($status, self::VALID_STATUSES, true);
    }

    /**
     * @return array<string,string> mapa id => status
     */
    public static function all(): array
    {
        $file = self::path();

        if (!is_file($file)) {
            return [];
        }

        $data = json_decode((string)file_get_contents($file), true);

        return is_array($data) ? $data : [];
    }

    /**
     * @throws RuntimeException
     */
    public static function set(int $id, string $status): void
    {
        if (!self::isValidStatus($status)) {
            throw new InvalidArgumentException('Status inválido.');
        }

        $handle = fopen(self::path(), 'c+');

        if (!$handle) {
            throw new RuntimeException('Não foi possível abrir o arquivo de status.');
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                throw new RuntimeException('Não foi possível bloquear o arquivo de status.');
            }

            $data = json_decode((string)stream_get_contents($handle), true);
            $data = is_array($data) ? $data : [];
            $data[(string)$id] = $status;

            ftruncate($handle, 0);
            rewind($handle);

            if (fwrite($handle, json_encode($data)) === false) {
                throw new RuntimeException('Não foi possível gravar o status.');
            }

            fflush($handle);
            flock($handle, LOCK_UN);
        } finally {
            fclose($handle);
        }
    }
}
