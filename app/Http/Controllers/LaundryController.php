<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerLaundryCollection;
use App\Models\CustomerLaundry;
use App\Models\CustomerLaundryLine;
use App\Models\CustomerLaundryPayment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaundryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('laundry.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Item::all();

        return view('laundry.add', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $sum = 0;
        $after_discount_sum = 0;
        $discount_sum = 0;
        $tax = 0;
        $tax_rate = 0;
        $after_tax = 0;
        $customer_laundry = CustomerLaundry::create([
            'date_received' => $request->date_received,
            'customer_id' => $request->customer_id,
            'return_status' => 0,
            'payment_status' => 0,
            'total_amount' => 0,
            'discount_applied_amount' => 0,
            'total_after_discount' => 0,
            'due_date' => $request->due_date,
            'note' => $request->note,
            'total_amount_paid' => 0, 'received_by' => Auth::user()->id, 'tax' => 0, 'current_tax_rate' => 0, 'total_after_tax' => 0]);
        $items = $request->input('group-a');
        foreach ($items as $item) {
            $line_price = $item['price'];
            $line_quantity = $item['quantity'];
            $line_total = $line_price * $line_quantity;
            $line_discount = $item['discount'];
            $line_discount_amount = $line_total * ($line_discount / 100);
            $line_total_after_discount = $line_total - $line_discount_amount;
            $sum += $line_total;
            $after_discount_sum += $line_total_after_discount;
            $discount_sum += $line_discount_amount;
            $customer_laundry_line = CustomerLaundryLine::create([
                'customer_laundry_id' => $customer_laundry->id,
                'item_id' => $item['item_id'],
                'current_cost' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $line_total,
                'discount_applied' => $line_discount_amount,
                'total_after_discount' => $line_total_after_discount,
                'description' => $item['description']
            ]);
        }
        if (setting('site.uses_tax') == 1) {
            $tax_rate = floatval(setting('site.tax_rate'));
        }
        $tax = ($after_discount_sum * ($tax_rate / 100));
        $after_tax = $after_discount_sum + $tax;


        $customer_laundry->update([
            'total_amount' => $sum,
            'discount_applied_amount' => $discount_sum,
            'total_after_discount' => $after_discount_sum,
            'tax' => $tax,
            'current_tax_rate' => $tax_rate,
            'total_after_tax' => $after_tax
        ]);
        return ['customer_laundry' => $customer_laundry, 'customer_laundry_lines' => $customer_laundry->customer_laundry_lines];


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\CustomerLaundry $customerLaundry
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $customerLaundry = CustomerLaundry::find($id);
        return view('laundry.view', compact('customerLaundry'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\CustomerLaundry $customerLaundry
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerLaundry $customerLaundry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CustomerLaundry $customerLaundry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerLaundry $customerLaundry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CustomerLaundry $customerLaundry
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerLaundry $customerLaundry)
    {
        //
    }

    public function fetch(Request $request)
    {
        $order = $request->input('order')[0]['column'];
        $columns = ['id', 'customer', 'total_after_tax', 'total_amount_paid', 'balance', 'date_received', 'return_status'];
        $direction = $request->input('order')[0]['dir'] == 'asc' ? 'asc' : 'desc';
        $page = ($request->input('start') + $request->input('length')) / $request->input('length');
        $request->merge(['page' => $page]);
        $q = $request->input('search')['value'];
        $customer_laundries = CustomerLaundry::where('id', '>', 0);
        $customer_laundries->where('date_received', $q)->orWhereHas('customer', function ($query) use ($q) {
            $query->where('customers.name', 'like', '%' . $q . '%');

        })->with(['customer']);
        $customer_laundries->orderBy($columns[$order], $direction);
        return $customer_laundries->paginate($request->input('length'));

    }

    public function print($id)
    {

        $customerLaundry = CustomerLaundry::find($id);
        return view('laundry.print', compact('customerLaundry'));
    }

    public function thermal_print($id)
    {

        $customerLaundry = CustomerLaundry::find($id);
        return view('laundry.thermal_print', compact('customerLaundry'));
    }

    public function report()
    {
        $customerLaundries = CustomerLaundry::where('id', '<>', 0);
        if (request()->filled('customer_id') && request()->customer_id > 0) {
            $q = request()->customer_id;
            $customerLaundries = $customerLaundries->whereHas('customer', function ($query) use ($q) {
                $query->where('customers.id', $q);

            });
        }
        if (request()->filled('from') && request()->filled('to')) {
            $q = request()->customer_id;
            $customerLaundries = $customerLaundries->whereBetween('date_received', [request()->from, request()->to]);
        }
        $customerLaundries = $customerLaundries->get();

        return view('laundry.report', compact('customerLaundries'));
    }

    public function graph_report()
    {
        $month=date('m');
        $year=date('Y');
        $newCustomers=DB::table('customers');
        if (request()->filled('from') && request()->filled('to')) {
            $newCustomers = $newCustomers->whereBetween('created_at', [request()->from, request()->to]);
        }else{
            $newCustomers= $newCustomers->whereMonth('created_at',$month)->whereYear('created_at',$year);
        }
         $newCustomers=$newCustomers->count();

        $bestCustomers = DB::table('customer_laundries')
            ->selectRaw('customers.name as name, customers.phone as phone, sum(total_after_tax) as sum_totals, customer_id')
            ->join('customers', 'customers.id', '=', 'customer_laundries.customer_id')
            ->groupBy('customer_id', 'customers.name','customers.phone');
        if (request()->filled('from') && request()->filled('to')) {
            $bestCustomers = $bestCustomers->whereBetween('customer_laundries.date_received', [request()->from, request()->to]);
        }else{
            $bestCustomers=$bestCustomers->whereMonth('customer_laundries.date_received',$month)->whereYear('customer_laundries.date_received',$year);
        }
         $bestCustomers = $bestCustomers->limit(5)->get();

        $bestCustomerItems= DB::table('customer_laundry_lines')
            ->selectRaw('customers.name as name, customers.phone as phone, sum(quantity) as sum_quantity, customer_id')
            ->join('customer_laundries', 'customer_laundries.id', '=', 'customer_laundry_lines.customer_laundry_id')
            ->join('customers', 'customers.id', '=', 'customer_laundries.customer_id')
            ->groupBy('customer_laundry_id','customer_id', 'customers.name','customers.phone');
        if (request()->filled('from') && request()->filled('to')) {
            $bestCustomerItems = $bestCustomerItems->whereBetween('customer_laundries.date_received', [request()->from, request()->to]);
        }else{
            $bestCustomerItems=$bestCustomerItems->whereMonth('customer_laundries.date_received',$month)->whereYear('customer_laundries.date_received',$year);
        }
         $bestCustomerItems = $bestCustomerItems->limit(5)->get();

        $mostWashedItems= DB::table('customer_laundry_lines')
            ->selectRaw('items.name as name, sum(quantity) as sum_quantity, item_id')
            ->join('customer_laundries', 'customer_laundries.id', '=', 'customer_laundry_lines.customer_laundry_id')
            ->join('items', 'items.id', '=', 'customer_laundry_lines.item_id')
            ->groupBy('item_id', 'items.name');
        if (request()->filled('from') && request()->filled('to')) {
            $mostWashedItems = $mostWashedItems->whereBetween('customer_laundries.date_received', [request()->from, request()->to]);
        }else{
            $mostWashedItems=$mostWashedItems->whereMonth('customer_laundries.date_received',$month)->whereYear('customer_laundries.date_received',$year);
        }
         $mostWashedItems = $mostWashedItems->limit(5)->get();

        $totalAmountMade= DB::table('customer_laundries')
            ->selectRaw( 'sum(total_after_tax) as sum_total');
        if (request()->filled('from') && request()->filled('to')) {
            $totalAmountMade = $totalAmountMade->whereBetween('customer_laundries.date_received', [request()->from, request()->to]);
        }else{
            $totalAmountMade=$totalAmountMade->whereMonth('customer_laundries.date_received',$month)->whereYear('customer_laundries.date_received',$year);
        }
         $totalAmountMade= $totalAmountMade->value('sum_total');

         $totalActualAmountMade= DB::table('customer_laundries')
            ->selectRaw( 'sum(customer_laundries.total_amount_paid) as sum_total');
        if (request()->filled('from') && request()->filled('to')) {
            $totalActualAmountMade = $totalActualAmountMade->whereBetween('customer_laundries.date_received', [request()->from, request()->to]);
        }else{
            $totalActualAmountMade=$totalActualAmountMade->whereMonth('customer_laundries.date_received',$month)->whereYear('customer_laundries.date_received',$year);
        }
         $totalActualAmountMade= $totalActualAmountMade->value('sum_total');

        $totalItemsWashed= DB::table('customer_laundry_lines')
            ->selectRaw( 'sum(quantity) as sum_quantity')
        ->join('customer_laundries', 'customer_laundries.id', '=', 'customer_laundry_lines.customer_laundry_id')
        ->join('items', 'items.id', '=', 'customer_laundry_lines.item_id');
        if (request()->filled('from') && request()->filled('to')) {
            $totalItemsWashed = $totalItemsWashed->whereBetween('customer_laundries.date_received', [request()->from, request()->to]);
        }else{
            $totalItemsWashed=$totalItemsWashed->whereMonth('customer_laundries.date_received',$month)->whereYear('customer_laundries.date_received',$year);
        }
          $totalItemsWashed= $totalItemsWashed->value('sum_quantity');

        return view('laundry.graph_report',compact('newCustomers','bestCustomers','bestCustomerItems','mostWashedItems','totalAmountMade','totalItemsWashed','totalActualAmountMade'));
    }

    public function annual_revenue(request $request)
    {
        $year=date('Y');
        if($request->year>0){
            $year=$request->year;
        }
        $data = [];

        $data['labels'] = $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $data['datasets'][0]['label'] = 'Expected Income';
        foreach ($months as $key => $month) {
            $data['datasets'][0]['data'][$key] = CustomerLaundry::where(function ($query) use ($year, $key) {
                $query->whereYear('date_received', $year)
                    ->whereMonth('date_received', '=', $key + 1);
            })
                ->orWhere(function ($query) use ($year, $key) {
                    $query->whereYear('date_received', $year)
                        ->where('date_received', '=', $key + 1);
                })->sum('total_after_tax');
        }
        $data['datasets'][1]['label'] = 'Actual Income';
        foreach ($months as $key => $month) {
            $data['datasets'][1]['data'][$key] = CustomerLaundry::where(function ($query) use ($year, $key) {
                $query->whereYear('date_received', $year)
                    ->whereMonth('date_received', '=', $key + 1);
            })
                ->orWhere(function ($query) use ($year, $key) {
                    $query->whereYear('date_received', $year)
                        ->where('date_received', '=', $key + 1);
                })->sum('total_amount_paid');
        }
        return $data;
    }

    public function save_payment(Request $request)
    {
        $old_payment=CustomerLaundryPayment::where('customer_laundry_id',$request->customer_laundry_id)->sum('amount_paid');
        $customer_laundry=CustomerLaundry::where('id',$request->customer_laundry_id)->first();
        $total=$customer_laundry->total_after_tax;
        $balance=$total-$old_payment;
        $after_payment_balance=$balance-$request->amount;
        $total_old_payments=CustomerLaundryPayment::where('customer_laundry_id',$request->customer_laundry_id)->sum('amount_paid');

        if($customer_laundry->payment_status==1){
            return response()->json(['status' => 'error', 'message' => 'There is no outstanding debt. Payment Status is Full Paid', ]);
        }elseif($total_old_payments>=$total){
    $customer_laundry->update([
        'total_amount_paid'=>$total_old_payments,
        'payment_status'=>1,
    ]);
    return response()->json(['status' => 'error', 'message' => 'There is no outstanding debt. Payment Status Adjusted', ]);
}elseif(($total_old_payments+$request->amount)>$total){
    return response()->json(['status' => 'error', 'message' => 'The amount entered is more than the outstanding balance.', ]);
}
        $payment=CustomerLaundryPayment::create([
            'customer_laundry_id'=>$request->customer_laundry_id,
            'date'=>$request->received_date,
            'previous_debt'=>$balance,
            'amount_paid'=>$request->amount,
            'payment_mode'=>$request->payment_mode,
            'description'=>$request->description,
            'debt_after_payment'=>$after_payment_balance,
            'received_by'=>Auth::user()->id
        ]);
        $total_payments=$old_payment+$request->amount;
        $payment_status=$after_payment_balance==0?1:0;
        $adjustments=$customer_laundry->update([
            'total_amount_paid'=>$total_payments,
            'payment_status'=>$payment_status,
        ]);
        if($payment_status==1){
            return response()->json(['status' => 'success', 'message' => 'Payment Successful. You have fully paid your outstanding fee.','id'=>$payment->id ]);
        }else{
            return response()->json(['status' => 'success', 'message' => 'Payment Successful. Your outstanding Balance is '.$after_payment_balance,'id'=>$payment->id  ]);
        }




    }

    public function view_receipt(Request $request,$id)
    {
        $payment=CustomerLaundryPayment::find($id);
        $customerLaundry=CustomerLaundry::where('id',$payment->customer_laundry_id)->first();
        return view('laundry.thermal_receipt',compact('payment','customerLaundry'));

    }

}
