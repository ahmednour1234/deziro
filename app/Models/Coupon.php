<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    public function isActive()
    {
        return $this->status == 'active' && $this->expiry_date > Carbon::now();
    }
}
