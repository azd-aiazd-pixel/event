<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enum\TransactionType;
class Transaction extends Model
{
   protected $fillable = ['participant_id', 'order_id', 'event_id', 'amount', 'type'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

public function event()
{
    return $this->belongsTo(Event::class);
}
    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
        ];
    }
}
