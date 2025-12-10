<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'brand_id',
        'short_description',
        'full_description',
        'specs',
        'price',
        'sale_price',
        'main_image',
    ];

    protected $casts = [
        'specs' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(InstrumentImage::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->sale_price !== null ? (float) $this->sale_price : (float) $this->price;
    }

    public function getHasSaleAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
