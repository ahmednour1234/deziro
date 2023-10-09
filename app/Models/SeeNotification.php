<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeeNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'notification_id',
        'user_id',
        'see'
    ];
    
}
