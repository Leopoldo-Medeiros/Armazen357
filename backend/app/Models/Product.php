<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'origin',
        'roast_level',
        'grind_type',
        'sku',
        'price_b2c',
        'price_b2b',
        'min_wholesale_qty',
        'stock_qty',
        'images',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_b2c'          => 'decimal:2',
            'price_b2b'          => 'decimal:2',
            'images'             => 'array',
            'is_active'          => 'boolean',
            'min_wholesale_qty'  => 'integer',
            'stock_qty'          => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = strtoupper(Str::random(8));
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public static function roastLevelLabels(): array
    {
        return [
            'light'  => 'Clara',
            'medium' => 'Média',
            'dark'   => 'Escura',
        ];
    }

    public static function grindTypeLabels(): array
    {
        return [
            'whole_bean'  => 'Grão inteiro',
            'coarse'      => 'Grossa',
            'medium'      => 'Média',
            'fine'        => 'Fina',
            'extra_fine'  => 'Extra fina',
        ];
    }
}
