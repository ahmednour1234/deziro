<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'position',
        'is_required',
        'is_unique',
        'validation',
        'value_per_locale',
        'value_per_user',
        'is_filterable',
        'is_configurable',
        'is_visible_on_front',
        'is_user_defined',
        'swatch_type',
    ];

    /**
     * Get the options.
     */
    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterableAttributes(Builder $query): Builder
    {
        return $query->where('is_filterable', 1)
            ->where('swatch_type', '<>', 'image')
            ->orderBy('position');
    }
}
