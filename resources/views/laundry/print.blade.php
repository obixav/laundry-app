@extends('layouts/fullLayoutMaster')

@section('title', 'Invoice Print-'.'DRS'.str_pad($customerLaundry->id, 5, 0, STR_PAD_LEFT))

@section('page-style')
    <link rel="stylesheet" href="{{asset(mix('css/base/pages/app-invoice-print.css'))}}">
@endsection

@section('content')
    <div class="invoice-print p-3">
        <div class="invoice-header d-flex justify-content-between flex-md-row flex-column pb-2">
            <div>
                <div class="d-flex mb-1">
                    <img src="{{asset('images/logo/logo.png')}}" style="height:100px ">
                    <h3 class="text-primary invoice-logo">{{setting('site.title')}}</h3>
                </div>

                <p class="card-text mb-25">{!! setting('site.office_address') !!} </p>
                <p class="card-text mb-0">{{setting('site.office_phone')}}</p>
            </div>
            <div class="mt-md-0 mt-2">
                <h4 class="font-weight-bold text-right mb-1">INVOICE #{{'DRS'.str_pad($customerLaundry->id, 5, 0, STR_PAD_LEFT)}}</h4>
                <div class="invoice-date-wrapper mb-50">
                    <span class="invoice-date-title">Date Issued:</span>
                    <span class="font-weight-bold"> {{date('F j, Y',strtotime($customerLaundry->date_received))}}</span>
                </div>
                @php

                    $due_date=\Carbon\Carbon::parse($customerLaundry->date_received)->addDay(3);
                @endphp
                <div class="invoice-date-wrapper mb-50 ">
                    <span class="invoice-date-title">Date Gen.:</span>
                    <span class="font-weight-bold"> {{date('F j, Y')}}</span>
                </div>
                <div class="invoice-date-wrapper">
                    <span class="invoice-date-title">Due Date:</span>
                    <span class="font-weight-bold">{{date('F j, Y',strtotime($due_date))}}</span>
                </div>

            </div>
        </div>

        <hr class="my-2" />

        <div class="row pb-2">
            <div class="col-sm-6">
                <h6 class="mb-1">Invoice To:</h6>
                <h6 class="mb-25">{{$customerLaundry->customer->name}}</h6>
                <p class=" mb-25">{{$customerLaundry->customer->address}}</p>
                <p class=" mb-25">{{$customerLaundry->customer->phone}}</p>
                <p class=" mb-0">{{$customerLaundry->customer->email}}</p>
            </div>
            <div class="col-sm-6 mt-sm-0 mt-2">
                <h6 class="mb-1">Payment Details:</h6>
                <table>
                    <tbody>
                    <tr>
                        <td class="pr-1">Total Due:</td>
                        <td><strong>{!! $customerLaundry->payment_status==1?'Fully paid':'&#8358;'.$customerLaundry->total_after_tax-$customerLaundry->total_amount_paid!!}</strong></td>
                    </tr>
                    <tr>
                        <td class="pr-1">Bank name:</td>
                        <td>{{setting('site.bank_name')}}</td>
                    </tr>
                    <tr>
                        <td class="pr-1">Account Name:</td>
                        <td>{{setting('site.bank_account_title')}}</td>
                    </tr>
                    <tr>
                        <td class="pr-1">Account Number:</td>
                        <td>{{setting('site.bank_account_number')}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-responsive mt-2">
            <table class="table m-0">
                <thead>
                <tr>
                    <th class="py-1">Item</th>
                    <th class="py-1">Cost</th>
                    <th class="py-1">Quantity</th>
                    <th class="py-1">Total</th>
                    <th class="py-1">Discount</th>
                    <th class="py-1">After Discount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customerLaundry->customer_laundry_lines as $ll)
                    <tr class="@if($loop->last) border-bottom @endif">
                        <td class="py-1">
                            <p class="card-text fw-bold mb-25">{{$ll->item->name}}</p>
                            <p class="card-text text-nowrap">
                                {{$ll->description}}
                            </p>
                        </td>
                        <td class="py-1 ">
                            <span class="fw-bold">&#8358;{{$ll->current_cost}}</span>
                        </td>
                        <td class="py-1">
                            <span class="fw-bold">{{$ll->quantity}}</span>
                        </td>
                        <td class="py-1">
                            <span class="fw-bold">&#8358;{{$ll->total}}</span>
                        </td>
                        <td class="py-1">
                            <span class="fw-bold">&#8358;{{$ll->discount_applied}}</span>
                        </td>
                        <td class="py-1">
                            <span class="fw-bold">&#8358;{{$ll->total_after_discount}}</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="row invoice-sales-total-wrapper mt-3">
            <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                <p class="card-text mb-0">
                    <span class="font-weight-bold">Salesperson:</span> <span class="ml-75">{{$customerLaundry->author->name}}</span>
                </p>
            </div>
            <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
                <div class="invoice-total-wrapper">
                    <div class="invoice-total-item">
                        <p class="invoice-total-title" >Subtotal:</p>
                        <p class="invoice-total-amount"id="total-before-discount">&#8358;{{$customerLaundry->total_amount}}</p>
                    </div>
                    <div class="invoice-total-item">
                        <p class="invoice-total-title">Discount:</p>
                        <p class="invoice-total-amount" id="discount">&#8358;{{$customerLaundry->discount_applied_amount}}</p>
                    </div>
                    <hr class="my-50" />
                    <div class="invoice-total-item">
                        <p class="invoice-total-title">After Discount:</p>
                        <p class="invoice-total-amount" id="after_discount">&#8358;{{$customerLaundry->total_after_discount}}</p>
                    </div>
                    <hr class="my-50" />
                    <div class="invoice-total-item">
                        <p class="invoice-total-title">Tax Rate:</p>
                        <p class="invoice-total-amount" id="total-before-discount">{{$customerLaundry->current_tax_rate}}%</p>
                    </div>
                    <div class="invoice-total-item">
                        <p class="invoice-total-title">Tax:</p>
                        <p class="invoice-total-amount" id="tax">&#8358;{{$customerLaundry->tax}}</p>
                    </div>

                    <hr class="my-50" />
                    <div class="invoice-total-item">
                        <p class="invoice-total-title">Total:</p>
                        <p class="invoice-total-amount" id="after_tax">&#8358;{{$customerLaundry->after_tax}}</p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-2" />

        <div class="row">
            <div class="col-12">
                <span class="font-weight-bold">Note:</span>
                <span
                >{{$customerLaundry->note}}</span
                >
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{asset('js/scripts/pages/app-invoice-print.js')}}"></script>
@endsection
