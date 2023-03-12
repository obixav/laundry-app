<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DashboardReportService
{
    public $month;
    public $year;

    /**
     * @param string $month
     * @param string $year
     */
    public function __construct()
    {
        $this->month =date('m');
        $this->year =date('Y');
    }


    public function getTotalOrderAmount()
    {
        $totalAmountMade= DB::table('customer_laundries')
            ->selectRaw( 'sum(total_after_tax) as sum_total');

        $totalAmountMade=$totalAmountMade->whereMonth('customer_laundries.date_received',$this->month)->whereYear('customer_laundries.date_received',$this->year);

        return $totalAmountMade= $totalAmountMade->value('sum_total');
    }

    public function getTotalNewCustomers()
    {
        $newCustomers=DB::table('customers');

        $newCustomers= $newCustomers->whereMonth('created_at',$this->month)->whereYear('created_at',$this->year);

        return $newCustomers=$newCustomers->count();
    }
    public function getTotalCustomers()
    {
        $customers=DB::table('customers');

        return $customers=$customers->count();
    }
    public function getPendingOrders()
    {
        $orders=DB::table('customer_laundries')->where('return_status',0);

        return $orders=$orders->count();
    }
    public function getTotalNewOrders()
    {
        $newOrders=DB::table('customer_laundries');

        $newOrders= $newOrders->whereMonth('created_at',$this->month)->whereYear('created_at',$this->year);

        return $newOrders=$newOrders->count();
    }
    public function totalPaymentsReceived()
    {
        $newOrders=DB::table('customer_laundry_payments')->selectRaw( 'sum(amount_paid) as sum_total');

        $newOrders= $newOrders->whereMonth('created_at',$this->month)->whereYear('created_at',$this->year);

        return $newOrders=$newOrders->value('sum_total');
    }

    public function annualReportOrders()
    {
        return DB::table('customer_laundries')->select(
        DB::raw("
    count(id) as data,
    MIN(DATE_FORMAT(created_at, '%m-%Y')) as new_date,
    YEAR(created_at) year,
    MONTH(created_at) month,
    MONTHNAME(created_at) month_name
    ")
    )->whereYear('created_at',$this->year)
        ->groupBy('year', 'month','month_name')
        ->get();

    }
    public function annualReportSales()
    {
        return DB::table('customer_laundries')->select(
            DB::raw("
    sum(total_after_tax) as sum_total,
    MIN(DATE_FORMAT(created_at, '%m-%Y')) as new_date,
    YEAR(created_at) year,
    MONTH(created_at) month,
    MONTHNAME(created_at) month_name
    ")
        )->whereYear('created_at',$this->year)
            ->groupBy('year', 'month','month_name')
            ->get();

    }
    public function monthlyReportItemCount()
    {
        return DB::table('customer_laundry_lines')->select(
            DB::raw("
    sum(quantity) as quantity_sum,
    items.id as item_id,
    items.name as name
    ")
        )->join('items', 'customer_laundry_lines.item_id', '=', 'items.id')->whereYear('customer_laundry_lines.created_at',$this->year)
            ->orderByDesc('quantity_sum')
            ->groupBy('items.id','items.name')
            ->get();

    }
    public function monthlyReportItemAmount()
    {
        return DB::table('customer_laundry_lines')->select(
            DB::raw("
    sum(total_after_discount) as sum_total,
    items.id as item_id,
    items.name as name
    ")
        )->join('items', 'customer_laundry_lines.item_id', '=', 'items.id')->whereYear('customer_laundry_lines.created_at',$this->year)
            ->orderByDesc('sum_total')
            ->groupBy('items.id','items.name')
            ->get();

    }


}
