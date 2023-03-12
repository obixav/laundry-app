<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CustomerLaundry extends Model
{
    protected $fillable=['id',
        'date_received',
        'customer_id',
        'date_returned',
        'return_status',
        'payment_status',
        'total_amount',
        'discount_applied_amount',
        'total_after_discount',
        'created_at',
        'updated_at',
        'deleted_at',
        'note',
        'due_date',
        'total_amount_paid','received_by','tax','current_tax_rate','total_after_tax'];
protected $appends=['balance','amount_paid','after_tax'];
    public function customer_laundry_lines()
    {
        return $this->hasMany(CustomerLaundryLine::class,'customer_laundry_id');
    }

    public function customer_laundry_payments()
    {
        return $this->hasMany(CustomerLaundryPayment::class,'customer_laundry_id');
    }
    public function author()
    {
        return $this->belongsTo(User::class,'received_by');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

    public function getBalanceAttribute()
    {

        return number_format(floatval($this->total_after_tax)-floatval($this->total_amount_paid),2);
    }
    public function getAmountPaidAttribute()
    {
        return number_format(floatval($this->total_amount_paid),2);
    }
    public function getAfterTaxAttribute()
    {
        return number_format(floatval($this->total_after_tax),2);
    }
}
