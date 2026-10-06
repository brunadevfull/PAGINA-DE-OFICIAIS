<?php

namespace App\Services;

class ClassificationService
{
    private static array $validTypes = ['plasa', 'bono', 'escala', 'cardapio', 'outros', 'aviso'];
    private static array $validUnits = ['EAGM', '1DN'];

    public function classify(string $originalName, ?string $title = null, ?string $forcedType = null): array
    {
        $normalizedName  = $this->normalizeString($originalName);
        $normalizedTitle = $title ? $this->normalizeString($title) : '';
        $primaryText     = $normalizedName;
        $fullText        = trim("$normalizedName $normalizedTitle");

        $type = $this->detectType($primaryText, $forcedType);
        $category = $this->detectCategory($type, $fullText);
        $unit = $this->detectUnit($type, $primaryText, $normalizedTitle);
        $tags = $this->buildTags($type, $category, $unit);

        return compact('type', 'category', 'unit', 'tags');
    }

    public function normalizeUnit(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        $compact = strtoupper(preg_replace('/[^A-Z0-9]/i', '', trim($value)));
        if ($compact === 'EAGM') return 'EAGM';
        if ($compact === '1DN' || $compact === 'DN1') return '1DN';
        return null;
    }

    public function normalizeType(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        $sanitized = trim(iconv('UTF-8', 'ASCII//TRANSLIT', $value) ?: $value);
        $lower = strtolower($sanitized);

        foreach (self::$validTypes as $candidate) {
            if ($lower === $candidate) {
                return $candidate;
            }
            if (str_starts_with($lower, $candidate)) {
                $nextChar = $lower[strlen($candidate)] ?? '';
                if ($nextChar && ctype_alnum($nextChar)) {
                    continue;
                }
                return $candidate;
            }
        }
        return null;
    }

    private function detectType(string $primaryText, ?string $forcedType): string
    {
        if ($forcedType && in_array($forcedType, self::$validTypes, true)) {
            return $forcedType;
        }

        if (str_contains($primaryText, 'PLASA')) return 'plasa';
        if (str_contains($primaryText, 'BONO'))  return 'bono';
        if (str_contains($primaryText, 'CARDAPIO') || str_contains($primaryText, 'CARD')) return 'cardapio';
        if (str_contains($primaryText, 'ESCALA')) return 'escala';

        return 'outros';
    }

    private function detectCategory(string $type, string $fullText): ?string
    {
        if ($type !== 'escala') return null;
        if (str_contains($fullText, 'OFICIA') || str_contains($fullText, ' OF ')) return 'oficial';
        if (str_contains($fullText, 'PRACA') || str_contains($fullText, 'PRAC')) return 'praca';
        return null;
    }

    private function detectUnit(string $type, string $primaryText, string $secondaryText): ?string
    {
        if ($type !== 'cardapio') return null;

        foreach ([$primaryText, $secondaryText] as $text) {
            if (str_contains($text, '1DN') || str_contains($text, 'DN 1')) return '1DN';
            if (str_contains($text, 'EAGM') || str_contains($text, 'EAGS')) return 'EAGM';
        }
        return null;
    }

    private function buildTags(string $type, ?string $category, ?string $unit): array
    {
        $tags = [];

        match ($type) {
            'plasa'    => $tags[] = 'PLASA',
            'bono'     => $tags[] = 'BONO',
            'escala'   => $tags[] = 'ESCALA',
            'cardapio' => $tags[] = 'CARDÁPIO',
            default    => null,
        };

        if ($type === 'escala') {
            if ($category === 'oficial') $tags[] = 'OFICIAIS';
            if ($category === 'praca')   $tags[] = 'PRAÇAS';
        }
        if ($type === 'cardapio') {
            if ($unit === '1DN')  $tags[] = '1DN';
            if ($unit === 'EAGM') $tags[] = 'EAGM';
        }

        return array_values(array_unique($tags));
    }

    private function normalizeString(string $str): string
    {
        $upper = mb_strtoupper($str);
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT', $upper) ?: $upper;
        $clean = preg_replace('/[^A-Z0-9\s]/', ' ', $ascii);
        return trim(preg_replace('/\s+/', ' ', $clean));
    }
}
