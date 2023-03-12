<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CustomerLaundryPayment extends Model
{
    protected $fillable=['id',
        'customer_laundry_id',
        'date',
        'previous_debt',
        'amount_paid',
        'payment_mode',
        'description',
        'debt_after_payment',
        'received_by',
        'created_at',
        'updated_at',
        'deleted_at'];
    public function customer_laundry()
    {
        return $this->belongsTo(CustomerLaundry::class,'customer_laundry_id');
    }
    public function author()
    {
        return $this->belongsTo(User::class,'received_by');
    }

    public function getPaymentTypeAttribute()
    {
        $arr=[1=>'Cash',2=>'Bank Transfer',3=>'Debit Card',4=>'Credit Card'];
        return $arr[$this->payment_mode];

    }

}
