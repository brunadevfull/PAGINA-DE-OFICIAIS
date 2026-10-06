<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PostgresArray implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (is_array($value)) return $value;
        if ($value === null || $value === '{}') return [];

        // Parse PostgreSQL array literal: {"tag1","tag2"} or {tag1,tag2}
        $inner = trim($value, '{}');
        if ($inner === '') return [];

        preg_match_all('/"(?:[^"\\\\]|\\\\.)*"|[^,]+/', $inner, $matches);
        return array_map(fn($v) => trim($v, '"'), $matches[0]);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!is_array($value)) $value = [];

        // Build PostgreSQL array literal: {"val1","val2"}
        $escaped = array_map(fn($v) => '"' . addcslashes((string) $v, '"\\') . '"', $value);
        return '{' . implode(',', $escaped) . '}';
    }
}
