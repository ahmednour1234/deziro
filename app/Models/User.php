<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;



class User extends  Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    public function scopeStore($query)
    {
        return $query->where('type', 2);
    }

    public function isIndividual()
    {
        return $this->type == 1;
    }

    public function isStore()
    {
        return $this->type == 2;
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'user_id', 'id');
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = [

        'first_name',
        'last_name',
        'store_name',
        'phone',
        'certificate',
        'email',
        'password',
        'type',
        'fcm_token',
        'categories',
        'status',
        'is_active',
        'reason'
    ];

    public function address()
    {
        return $this->hasMany(Address::class, 'user_id', 'id');
    }
    public function notification()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }
    public function getExchangeRate()
    {
        if ($this->isStore())
            return $this->vendor_exchange_rate;
        else
            return 80000;
    }
}
