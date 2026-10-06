<?php

class DutyAssignmentsRepository
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
    public function getCurrentAssignment(): ?array
    {
        try {
            $statement = $this->pdo->query(
                'SELECT id, officer_name, officer_rank, master_name, master_rank, valid_from, updated_at
                 FROM duty_assignments
                 ORDER BY valid_from DESC, updated_at DESC
                 LIMIT 1'
            );

            $row = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            throw new RuntimeException('Falha ao consultar oficiais de serviço.', 0, $exception);
        }

        if (!is_array($row)) {
            return null;
        }

        return $this->normalizeAssignment($row);
    }

    /**
     * @throws RuntimeException
     */
    public function createAssignment(array $data): array
    {
        $officerName = $this->sanitizeNullableString($data['officerName'] ?? null);
        $officerRank = $this->sanitizeNullableString($data['officerRank'] ?? null);
        $masterName = $this->sanitizeNullableString($data['masterName'] ?? null);
        $masterRank = $this->sanitizeNullableString($data['masterRank'] ?? null);
        $validFrom = $data['validFrom'] ?? null;

        if ($validFrom instanceof DateTimeInterface) {
            $validFromString = $validFrom->format('Y-m-d H:i:s');
        } elseif (is_string($validFrom) && trim($validFrom) !== '') {
            $validFromString = $this->createDateTimeFromString($validFrom)->format('Y-m-d H:i:s');
        } else {
            $validFromString = (new DateTimeImmutable('now'))
                ->setTimezone(new DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s');
        }

        try {
            $statement = $this->pdo->prepare(
                'INSERT INTO duty_assignments (officer_name, officer_rank, master_name, master_rank, valid_from)
                 VALUES (:officer_name, :officer_rank, :master_name, :master_rank, :valid_from)
                 RETURNING id, officer_name, officer_rank, master_name, master_rank, valid_from, updated_at'
            );

            $statement->execute([
                ':officer_name' => $officerName,
                ':officer_rank' => $officerRank,
                ':master_name' => $masterName,
                ':master_rank' => $masterRank,
                ':valid_from' => $validFromString,
            ]);

            $row = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            throw new RuntimeException('Falha ao salvar oficiais de serviço.', 0, $exception);
        }

        if (!is_array($row)) {
            throw new RuntimeException('Não foi possível recuperar o registro salvo.');
        }

        return $this->normalizeAssignment($row);
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
            throw new RuntimeException('Não foi possível conectar ao banco de oficiais de serviço.', 0, $exception);
        }

        return $pdo;
    }

    private function normalizeAssignment(array $row): array
    {
        $validFrom = $this->formatDateTime($row['valid_from'] ?? null);
        $updatedAt = $this->formatDateTime($row['updated_at'] ?? null);

        return [
            'id' => isset($row['id']) ? (int)$row['id'] : null,
            'officerName' => $this->sanitizeNullableString($row['officer_name'] ?? null),
            'officerRank' => $this->sanitizeNullableString($row['officer_rank'] ?? null),
            'masterName' => $this->sanitizeNullableString($row['master_name'] ?? null),
            'masterRank' => $this->sanitizeNullableString($row['master_rank'] ?? null),
            'validFrom' => $validFrom,
            'updatedAt' => $updatedAt,
        ];
    }

    private function sanitizeNullableString($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim((string)$value);

        return $trimmed === '' ? null : $trimmed;
    }

    private function formatDateTime(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        try {
            return $this->createDateTimeFromString($value)
                ->setTimezone(new DateTimeZone('UTC'))
                ->format(DateTimeInterface::ATOM);
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * @throws RuntimeException
     */
    private function createDateTimeFromString(string $value): DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($value);
        } catch (Exception $exception) {
            throw new RuntimeException('Data/hora inválida informada.', 0, $exception);
        }
    }
}
