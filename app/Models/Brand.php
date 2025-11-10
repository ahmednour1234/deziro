<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'brands';
    protected $fillable = [
        'name',
        'image_path'
    ];

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * The categories that belong to the brand.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_brands');
    }
}
