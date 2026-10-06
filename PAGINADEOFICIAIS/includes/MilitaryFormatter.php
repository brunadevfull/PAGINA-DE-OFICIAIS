<?php

class MilitaryFormatter
{
    public static function formatRank(?string $rank): string
    {
        $normalized = self::normalizeString($rank);

        return $normalized;
    }

    public static function formatSpecialty(?string $specialty): string
    {
        if ($specialty === null) {
            return '';
        }

        $stripped = trim((string)$specialty);
        // Remove parênteses existentes para evitar duplicidade
        $stripped = trim($stripped, " \t\n\r\0\x0B()");

        return self::normalizeString($stripped);
    }

    public static function formatName(?string $name): string
    {
        return self::normalizeString($name);
    }

    public static function buildRankWithSpecialty(?string $rank, ?string $specialty = null): string
    {
        $formattedRank = self::formatRank($rank);
        $formattedSpecialty = self::formatSpecialty($specialty);

        if ($formattedRank === '' && $formattedSpecialty === '') {
            return '';
        }

        if ($formattedRank === '') {
            return '(' . $formattedSpecialty . ')';
        }

        if ($formattedSpecialty === '') {
            return $formattedRank;
        }

        if (stripos($formattedRank, $formattedSpecialty) !== false) {
            return $formattedRank;
        }

        return sprintf('%s (%s)', $formattedRank, $formattedSpecialty);
    }

    public static function buildDisplayName(?string $rank, ?string $name, ?string $specialty = null): string
    {
        $rankWithSpecialty = self::buildRankWithSpecialty($rank, $specialty);
        $formattedName = self::formatName($name);

        $parts = [];

        if ($rankWithSpecialty !== '') {
            $parts[] = $rankWithSpecialty;
        }

        if ($formattedName !== '') {
            $parts[] = $formattedName;
        }

        return trim(implode(' ', $parts));
    }

    private static function normalizeString(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $trimmed = trim($value);

        if ($trimmed === '') {
            return '';
        }

        if (function_exists('mb_strtoupper')) {
            return mb_strtoupper($trimmed, 'UTF-8');
        }

        return strtoupper($trimmed);
    }
}
