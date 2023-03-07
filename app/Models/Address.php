<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    use HasFactory;
    protected $fillable = ['id', 'user_id', 'address_details','location','is_default'];

    public function user_address(){
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
