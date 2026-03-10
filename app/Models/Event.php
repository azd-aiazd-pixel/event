<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory,softDeletes;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts()
    {
        return [
            'start_date' => 'datetime',
            'end_date'   => 'datetime',
            'is_active'  => 'boolean',
        ];
    }

  

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

public function isActive()
    {
      
        return $this->is_active; 
    }
}