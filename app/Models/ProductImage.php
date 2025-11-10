<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'product_image',
        'product_id',
        'sort',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get image url for the product image.
     *
     * @return string
     */
    public function url()
    {
        return Storage::url($this->product_image);
    }
}
