<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use SoftDeletes;
    protected $fillable = ['store_id', 'name','picture', 'unit_price', 'quantity', 'is_active','category_id',  'unit_measure_id','is_stockable'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_stockable' => 'boolean',
        'unit_price' => 'decimal:2', 
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unitMeasure()
    {
        return $this->belongsTo(UnitMeasure::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(Participant::class, 'wishlists')->withTimestamps();
    }
}
