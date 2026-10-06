<?php

require_once __DIR__ . '/MilitaryFormatter.php';

class MilitaryPersonnelRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo !== null) {
            $this->pdo = $pdo;
            return;
        }

        $this->pdo = $this->createConnectionFromEnv();
    }

    /**
     * @throws RuntimeException
     */
    public function getPersonnelOptions(string $type): array
    {
        $type = trim($type);

        if ($type === '') {
            throw new InvalidArgumentException('Tipo de militar inválido informado.');
        }

        try {
            $statement = $this->pdo->prepare(
                'SELECT * FROM military_personnel WHERE type = :type'
            );
            $statement->execute([':type' => $type]);
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            throw new RuntimeException('Falha ao consultar militares no banco de dados.', 0, $exception);
        }

        $options = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $normalized = $this->normalizePersonnel($row, $type);

            if ($normalized['display'] === '') {
                continue;
            }

            $optionKey = $this->buildOptionKey($normalized);

            if (isset($options[$optionKey])) {
                continue;
            }

            $options[$optionKey] = [
                'id' => $normalized['id'],
                'value' => $normalized['name'],
                'name' => $normalized['name'],
                'rank' => $normalized['rank_display'],
                'short_rank' => $normalized['rank'],
                'type' => $normalized['type'],
                'specialty' => $normalized['specialty'],
                'display' => $normalized['display'],
            ];
        }

        $options = array_values($options);

        usort($options, static function (array $left, array $right): int {
            return strcmp($left['display'], $right['display']);
        });

        return $options;
    }

    /**
     * @throws RuntimeException
     */
    private function createConnectionFromEnv(): PDO
    {
        $databaseUrl = getenv('DATABASE_URL') ?: 'postgresql://postgres:suasenha123@localhost:5432/marinha_papem';
        $parts = parse_url($databaseUrl);

        if ($parts === false || !isset($parts['scheme'])) {
            throw new RuntimeException('DATABASE_URL inválida ou ausente.');
        }

        $scheme = strtolower($parts['scheme']);

        if ($scheme !== 'postgresql' && $scheme !== 'postgres') {
            throw new RuntimeException('DATABASE_URL deve utilizar o driver PostgreSQL.');
        }

        $host = $parts['host'] ?? 'localhost';
        $port = (int)($parts['port'] ?? 5432);
        $user = isset($parts['user']) ? urldecode($parts['user']) : '';
        $password = isset($parts['pass']) ? urldecode($parts['pass']) : '';
        $database = isset($parts['path']) ? ltrim($parts['path'], '/') : '';

        if ($database === '') {
            throw new RuntimeException('DATABASE_URL não contém o nome do banco de dados.');
        }

        $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s', $host, $port, $database);

        try {
            $pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException('Não foi possível conectar ao banco de militares.', 0, $exception);
        }

        return $pdo;
    }

    private function normalizePersonnel(array $row, string $requestedType): array
    {
        $name = $row['name'] ?? $row['nome'] ?? $row['fullName'] ?? $row['full_name'] ?? '';
        $rank = $row['rank'] ?? $row['posto'] ?? $row['descricao'] ?? $row['patente'] ?? '';
        $type = $row['type'] ?? $requestedType;
        $specialty = $row['specialty'] ?? $row['especialidade'] ?? '';
        $identifier = $row['id'] ?? null;

        $formattedName = MilitaryFormatter::formatName((string)$name);
        $formattedRank = MilitaryFormatter::formatRank((string)$rank);
        $formattedSpecialty = MilitaryFormatter::formatSpecialty((string)$specialty);

        return [
            'id' => $identifier !== null ? (int)$identifier : null,
            'name' => $formattedName,
            'rank' => $formattedRank,
            'rank_display' => MilitaryFormatter::buildRankWithSpecialty($formattedRank, $formattedSpecialty),
            'type' => trim((string)$type),
            'specialty' => $formattedSpecialty,
            'display' => MilitaryFormatter::buildDisplayName($formattedRank, $formattedName, $formattedSpecialty),
        ];
    }

    private function buildOptionKey(array $normalized): string
    {
        $keyParts = [
            $normalized['id'] ?? '',
            $normalized['name'] ?? '',
            $normalized['rank_display'] ?? ($normalized['rank'] ?? ''),
            $normalized['type'] ?? '',
        ];

        $normalizedParts = array_map(static function ($part) {
            $part = (string)$part;

            if (function_exists('mb_strtolower')) {
                return mb_strtolower($part, 'UTF-8');
            }

            return strtolower($part);
        }, $keyParts);

        return implode('|', $normalizedParts);
    }
}
