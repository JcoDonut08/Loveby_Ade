<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

class AdminAnalyticsPeriod
{
    public const TODAY = 'today';

    public const WEEK = 'week';

    public const MONTH = 'month';

    public const YEAR = 'year';

    /**
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return [
            self::TODAY,
            self::WEEK,
            self::MONTH,
            self::YEAR,
        ];
    }

    /**
     * @return array{start: CarbonImmutable, end: CarbonImmutable, previous_start: CarbonImmutable, previous_end: CarbonImmutable}
     */
    public function rangeFor(string $period): array
    {
        $now = $this->now();

        return match ($period) {
            self::TODAY => [
                'start' => $now->startOfDay(),
                'end' => $now->endOfDay(),
                'previous_start' => $now->subDay()->startOfDay(),
                'previous_end' => $now->subDay()->endOfDay(),
            ],
            self::MONTH => [
                'start' => $now->startOfMonth(),
                'end' => $now->endOfMonth(),
                'previous_start' => $now->subMonthNoOverflow()->startOfMonth(),
                'previous_end' => $now->subMonthNoOverflow()->endOfMonth(),
            ],
            self::YEAR => [
                'start' => $now->startOfYear(),
                'end' => $now->endOfYear(),
                'previous_start' => $now->subYear()->startOfYear(),
                'previous_end' => $now->subYear()->endOfYear(),
            ],
            default => [
                'start' => $now->startOfWeek(),
                'end' => $now->endOfWeek(),
                'previous_start' => $now->subWeek()->startOfWeek(),
                'previous_end' => $now->subWeek()->endOfWeek(),
            ],
        };
    }

    /**
     * @return array<int, array{key: string, label: string, url: string, active: bool}>
     */
    public function options(string $activePeriod, string $search): array
    {
        return collect([
            self::TODAY => 'Today',
            self::WEEK => 'This Week',
            self::MONTH => 'This Month',
            self::YEAR => 'This Year',
        ])->map(fn (string $label, string $period): array => [
            'key' => $period,
            'label' => $label,
            'url' => route('admin.analytics', array_filter([
                'period' => $period,
                'search' => $search !== '' ? $search : null,
            ], fn (?string $value): bool => $value !== null)),
            'active' => $period === $activePeriod,
        ])->values()->all();
    }

    public function noun(string $period): string
    {
        return match ($period) {
            self::TODAY => 'today',
            self::MONTH => 'month',
            self::YEAR => 'year',
            default => 'week',
        };
    }

    public function time(?CarbonInterface $time): ?CarbonImmutable
    {
        return $time instanceof CarbonInterface
            ? CarbonImmutable::instance($time)->timezone($this->timezone())
            : null;
    }

    private function now(): CarbonImmutable
    {
        return CarbonImmutable::instance(now($this->timezone()));
    }

    private function timezone(): string
    {
        return (string) config('app.business_timezone', 'Asia/Manila');
    }
}
