<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeOption extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'name',
        'swatch_value',
        'sort_order',
        'attribute_id',
        'user_id',
    ];

    /**
     * Get the attribute that owns the attribute option.
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get image url for the swatch value url.
     */
    public function swatch_value_url()
    {
        if (
            $this->swatch_value
            && $this->attribute->swatch_type == 'image'
        ) {
            return url($this->swatch_value);
        }

        return null;
    }

    /**
     * Get image url for the product image.
     */
    public function getSwatchValueUrlAttribute()
    {
        return $this->swatch_value_url();
    }
}
