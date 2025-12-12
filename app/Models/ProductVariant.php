<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\App;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_slug',
        'price',
        'discount_price',
        'stock',
        'SKU',
        'barcode',
        'is_primary',
        'weight',
        'dimensions',
    ];

    protected $appends = ['converted_price', 'converted_discount_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function translations()
    {
        return $this->hasMany(ProductVariantTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(ProductVariantTranslation::class)
            ->where('language_code', App::getLocale());
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'variant_id');
    }

    public function getConvertedPriceAttribute()
    {
        return convert_price($this->price);
    }

    public function getConvertedDiscountPriceAttribute()
    {
        return $this->discount_price ? convert_price($this->discount_price) : null;
    }

    /*public function attributeValues()
    {
        return $this->belongsToMany(
            \App\Models\AttributeValue::class,
            'product_attribute_values',
            'product_id',
            'attribute_value_id'
        )->withTimestamps();
    }*/

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values');
    }

    /**
     * Check if this variant is on sale (has a discount price lower than regular price)
     */
    public function getIsOnSaleAttribute(): bool
    {
        return $this->discount_price !== null 
            && $this->discount_price > 0 
            && $this->discount_price < $this->getEffectivePrice();
    }

    /**
     * Get the effective price (with fallback to product's primary variant price)
     */
    public function getEffectivePrice(): float
    {
        if ($this->price && $this->price > 0) {
            return $this->price;
        }
        
        // Fallback to product's primary variant price (avoid infinite loop by checking is_primary)
        if (!$this->is_primary && $this->product) {
            $primary = $this->product->variants()->where('is_primary', true)->first();
            if ($primary && $primary->id !== $this->id) {
                return $primary->price ?? 0;
            }
        }
        
        return 0;
    }

    /**
     * Get the display price (discount price if on sale, otherwise regular price)
     */
    public function getDisplayPriceAttribute(): float
    {
        if ($this->is_on_sale) {
            return $this->discount_price;
        }
        return $this->getEffectivePrice();
    }

    /**
     * Get converted display price
     */
    public function getConvertedDisplayPriceAttribute()
    {
        return convert_price($this->display_price);
    }
}
