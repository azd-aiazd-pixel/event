<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Participant extends Model
{use HasFactory;
use SoftDeletes;
   protected $fillable = ['user_id', 'balance','reserved_balance','event_id','nfc_code',];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getAvailableBalanceAttribute()
    {
        return $this->balance - $this->reserved_balance;
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function event()
{
    return $this->belongsTo(Event::class);
}



public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }
}
