<?php

namespace App\Http\Controllers;

use App\Services\DashboardReportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  // Dashboard - Analytics
  public function dashboardAnalytics()
  {
    $pageConfigs = ['pageHeader' => false];

    return view('/content/dashboard/dashboard-analytics', ['pageConfigs' => $pageConfigs]);
  }

  // Dashboard - Ecommerce
  public function dashboardEcommerce()
  {
    $pageConfigs = ['pageHeader' => false];
    $report=(new DashboardReportService());
 $iqs=$report->monthlyReportItemCount();
// return $total=$iqs->sum('quantity_sum');

    return view('/content/dashboard/dashboard-ecommerce', ['pageConfigs' => $pageConfigs,
    'total_sales'=>$report->getTotalOrderAmount(),'new_customers'=>$report->getTotalNewCustomers(),
        'total_customers'=>$report->getTotalCustomers(),'unreturned_orders'=>$report->getPendingOrders(),
        'total_new_orders'=>$report->getTotalNewOrders(),'payments_received'=>$report->totalPaymentsReceived(),
        'sales_trend'=>$report->annualReportSales(),'order_trend'=>$report->annualReportOrders(),
        'month_item_quantity'=>$report->monthlyReportItemCount(),'month_item_amount'=>$report->monthlyReportItemAmount()]);
  }
}
