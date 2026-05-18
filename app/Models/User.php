<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'google_id',
        'name',
        'email',
        'email_verified_at',
        'last_active_at',
        'contact_number',
        'address',
        'password',
        'role',
        'profile_photo_path',
        'google_avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'google_id',
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_active_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return HasMany<CartItem, $this>
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return HasMany<FavoriteItem, $this>
     */
    public function favoriteItems(): HasMany
    {
        return $this->hasMany(FavoriteItem::class);
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasMany<ProductReview, $this>
     */
    public function productReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * @return HasMany<ProductReviewLike, $this>
     */
    public function productReviewLikes(): HasMany
    {
        return $this->hasMany(ProductReviewLike::class);
    }

    /**
     * @return HasMany<ProductReviewReply, $this>
     */
    public function productReviewReplies(): HasMany
    {
        return $this->hasMany(ProductReviewReply::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function profilePhotoUrl(): ?string
    {
        if ($this->profile_photo_path) {
            return Storage::disk('public')->url($this->profile_photo_path);
        }

        return $this->google_avatar_url;
    }
}
