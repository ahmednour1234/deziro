<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerImage extends Model
{
    use HasFactory;

    protected $fillable = ['banner_id','id','image'];

    public function banners(){
        return $this->belongsTo(Banner::class, 'banner_id','id');
    }
}
