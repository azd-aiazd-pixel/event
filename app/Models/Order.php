<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    protected $fillable = [
        'store_id',
        'participant_id',
        'total_points',
        'status',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

}
