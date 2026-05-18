<?php

namespace App\Models;

use Database\Factories\ProductReviewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProductReview extends Model
{
    /** @use HasFactory<ProductReviewFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'author_name',
        'rating',
        'body',
        'media_paths',
        'is_anonymous',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'media_paths' => 'array',
            'is_anonymous' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<ProductReviewLike, $this>
     */
    public function likes(): HasMany
    {
        return $this->hasMany(ProductReviewLike::class);
    }

    /**
     * @return HasMany<ProductReviewReply, $this>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ProductReviewReply::class);
    }

    public function displayName(): string
    {
        if (! $this->is_anonymous) {
            return $this->author_name;
        }

        return Str::of($this->author_name)
            ->squish()
            ->explode(' ')
            ->filter()
            ->map(fn (string $namePart): string => $this->maskNamePart($namePart))
            ->implode(' ');
    }

    private function maskNamePart(string $namePart): string
    {
        $length = mb_strlen($namePart);

        if ($length <= 1) {
            return '**';
        }

        if ($length === 2) {
            return mb_substr($namePart, 0, 1).'**';
        }

        return mb_substr($namePart, 0, 1).'**'.mb_substr($namePart, -1);
    }
}
