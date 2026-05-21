<?php

namespace App\Services;

use App\Models\Promotion;

class PromotionService
{
    public function findAvailable(?string $code): ?Promotion
    {
        $normalizedCode = $this->normalizeCode($code);

        if ($normalizedCode === null) {
            return null;
        }

        $promotion = Promotion::query()
            ->where('code', $normalizedCode)
            ->where('kind', Promotion::KIND_DISCOUNT)
            ->first();

        if (! $promotion instanceof Promotion || ! $promotion->isAvailable()) {
            return null;
        }

        return $promotion;
    }

    public function normalizeCode(?string $code): ?string
    {
        $normalizedCode = str((string) $code)->trim()->upper()->toString();

        return $normalizedCode === '' ? null : $normalizedCode;
    }

    public function formatPeso(float $amount): string
    {
        return "\u{20B1}".number_format($amount, 2);
    }
}
