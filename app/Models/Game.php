<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'title',
        'platform',
        'genre',
        'offer_type',
        'condition',
        'price',
        'stock_total',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowings()
    {
        return $this->hasMany(Borrowing::class)->whereNull('returned_at');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, function (Builder $builder, string $value): void {
            $builder->where('title', 'like', '%'.$value.'%');
        });
    }

    public function getAvailableStockAttribute(): int
    {
        $activeBorrowings = (int) ($this->active_borrowings_count ?? 0);

        return max(0, $this->stock_total - $activeBorrowings);
    }

    public function canBePurchased(int $quantity = 1): bool
    {
        return in_array($this->offer_type, ['vente', 'location_vente'], true)
            && $this->available_stock >= $quantity;
    }

    public function canBeBorrowed(): bool
    {
        return in_array($this->offer_type, ['location', 'location_vente'], true)
            && $this->available_stock > 0;
    }
}
