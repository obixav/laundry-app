<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CustomerLaundryLine extends Model
{
    protected $fillable=['id',
        'customer_laundry_id',
        'item_id',
        'current_cost',
        'quantity',
        'total',
        'discount_applied',
        'total_after_discount',
        'created_at',
        'updated_at',
        'deleted_at',
        'description'];

    public function customer_laundry()
    {
        return $this->belongsTo(CustomerLaundry::class,'customer_laundry_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }
}
