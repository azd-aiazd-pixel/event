<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Store extends Model
{use HasFactory;
use SoftDeletes;
    protected $fillable = ['user_id','event_id', 'name', 'logo', 'status','workflow_type',  'theme_primary_color', 'theme_bg_color', 'theme_text_color', 'theme_bg_image', 'theme_body_bg_image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

 
public function isActive()
    {
        return $this->status === 'active';
    }


    public function isQueue()
    {
        return $this->workflow_type === 'queue';
    }

    public function isDirect()
    {
        return $this->workflow_type === 'direct';
    }

}
