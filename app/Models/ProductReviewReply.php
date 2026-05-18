<?php

namespace App\Models;

use Database\Factories\ProductReviewReplyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReviewReply extends Model
{
    /** @use HasFactory<ProductReviewReplyFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'product_review_id',
        'user_id',
        'body',
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
