<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\PromotionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    /** @use HasFactory<PromotionFactory> */
    use HasFactory;

    public const DISCOUNT_FIXED = 'fixed';

    public const DISCOUNT_PERCENTAGE = 'percentage';

    public const KIND_AD = 'ad';

    public const KIND_DISCOUNT = 'discount';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'kind',
        'discount_type',
        'discount_value',
        'starts_at',
        'expires_at',
        'is_active',
        'announcement_title',
        'announcement_body',
        'announcement_cta',
        'image_path',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'starts_at' => 'date',
            'expires_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isAvailable(?CarbonInterface $at = null): bool
    {
        $date = $at ?? now();

        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at !== null && $this->starts_at->isAfter($date)) {
            return false;
        }

        if ($this->expires_at !== null && $this->expires_at->copy()->endOfDay()->isBefore($date)) {
            return false;
        }

        return true;
    }

    public function discountFor(float $subtotal): float
    {
        if ($this->kind !== self::KIND_DISCOUNT || $subtotal <= 0 || ! $this->isAvailable()) {
            return 0.0;
        }

        $value = (float) $this->discount_value;

        if ($this->discount_type === self::DISCOUNT_PERCENTAGE) {
            return round(min($subtotal, $subtotal * ($value / 100)), 2);
        }

        return round(min($subtotal, $value), 2);
    }

    public function discountLabel(): string
    {
        if ($this->kind === self::KIND_AD) {
            return 'Image ad';
        }

        if ($this->discount_type === self::DISCOUNT_PERCENTAGE) {
            return rtrim(rtrim(number_format((float) $this->discount_value, 2), '0'), '.').'% off';
        }

        return "\u{20B1}".number_format((float) $this->discount_value, 2).' off';
    }

    public function validityLabel(): string
    {
        $startsAt = $this->starts_at?->format('Y-m-d') ?? 'Now';
        $expiresAt = $this->expires_at?->format('Y-m-d') ?? 'No expiry';

        return $startsAt.' to '.$expiresAt;
    }

    public function statusLabel(): string
    {
        if (! $this->is_active) {
            return 'Inactive';
        }

        if ($this->starts_at !== null && $this->starts_at->isFuture()) {
            return 'Scheduled';
        }

        if ($this->expires_at !== null && $this->expires_at->copy()->endOfDay()->isPast()) {
            return 'Expired';
        }

        return 'Active';
    }
}
