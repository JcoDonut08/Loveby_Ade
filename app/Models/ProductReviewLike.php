<?php

namespace App\Models;

use Database\Factories\ProductReviewLikeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReviewLike extends Model
{
    /** @use HasFactory<ProductReviewLikeFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'product_review_id',
        'user_id',
    ];

    /**
     * @return BelongsTo<ProductReview, $this>
     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(ProductReview::class, 'product_review_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
