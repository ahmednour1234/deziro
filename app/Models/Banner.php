<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;


    protected $fillable = ['id','name'];

    public function images()
    {
        return $this->hasMany(BannerImage::class, 'banner_id', 'id');
    }

}
