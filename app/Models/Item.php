<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Item extends Model
{

    public function customer_laundry_lines()
    {
        return $this->hasMany(CustomerLaundryLine::class,'customer_laundry_id');
    }
}
