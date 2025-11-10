<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedProducts extends Model
{
    use HasFactory;
    protected $table = 'featured_products';
    protected $fillable = [
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
