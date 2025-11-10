<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'name',
        'image',
    ];
    public function product()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    /**
     * The brands that belong to the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'category_brands');
    }

    /**
     * The filterable attributes that belong to the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function filterableAttributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'category_filterable_attributes', 'category_id')
            ->with([
                'options' => function ($query) {
                    $query->orderBy('sort_order');
                },
            ]);
    }
}
