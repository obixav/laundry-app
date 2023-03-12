@extends('layouts/contentLayoutMaster')

@section('title', 'Invoice Preview')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('vendors/css/pickers/flatpickr/flatpickr.min.css')}}">

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection
@section('page-style')
    <link rel="stylesheet" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice.css')}}">
@endsection

@section('content')
    <section class="invoice-preview-wrapper">
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-xl-9 col-md-8 col-12">
                <div class="card invoice-preview-card">
                    <div class="card-body invoice-padding pb-0">
                        <!-- Header starts -->
                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                            <div>
                                <div class="logo-wrapper">
                                    <img src="{{asset('images/logo/logo.png')}}" style="height:100px ">
                                    <h3 class="text-primary invoice-logo">{{setting('site.title')}}</h3>
                                </div>
                                <p class="card-text mb-25">{!! setting('site.office_address') !!} </p>
                                <p class="card-text mb-0">{{setting('site.office_phone')}}</p>
                            </div>
                            <div class="mt-md-0 mt-2">
                                <h4 class="invoice-title">
                                    Invoice
                                    <span class="invoice-number">#{{'DRS'.str_pad($customerLaundry->id, 5, 0, STR_PAD_LEFT)}}</span>
                                </h4>
                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title">Date Issued:</p>
                                    <p class="invoice-date">{{date('F j, Y',strtotime($customerLaundry->date_received))}}</p>
                                </div>
                                @php

                                $due_date=\Carbon\Carbon::parse($customerLaundry->date_received)->addDay(3);
                                @endphp
                                <div class="invoice-date-wrapper ">
                                    <p class="invoice-date-title">Date Gen.:</p>
                                    <p class="invoice-date"> {{date('F j, Y')}}</p>
                                </div>
                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title">Due Date:</p>
                                    <p class="invoice-date">{{date('F j, Y',strtotime($due_date))}}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Header ends -->
                    </div>

                    <hr class="invoice-spacing" />

                    <!-- Address and Contact starts -->
                    <div class="card-body invoice-padding pt-0">
                        <div class="row invoice-spacing">
                            <div class="col-xl-8 p-0">
                                <h6 class="mb-2">Invoice To:</h6>
                                <h6 class="mb-25">{{$customerLaundry->customer->name}}</h6>
                                <p class="card-text mb-25">{{$customerLaundry->customer->address}}</p>
                                <p class="card-text mb-25">{{$customerLaundry->customer->phone}}</p>
                                <p class="card-text mb-0">{{$customerLaundry->customer->email}}</p>
                            </div>
                            <div class="col-xl-4 p-0 mt-xl-0 mt-2">
                                <h6 class="mb-2">Payment Details:</h6>
                                <table>
                                    <tbody>
                                    <tr>
                                        <td class="pe-1">Total Due:</td>
                                        <td><span class="fw-bold">{!! $customerLaundry->payment_status==1?'Fully paid':'&#8358;'.$customerLaundry->total_after_tax-$customerLaundry->total_amount_paid!!}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="pe-1">Bank name:</td>
                                        <td>{{setting('site.bank_name')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-1">Account Name:</td>
                                        <td>{{setting('site.bank_account_title')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-1">Account Number:</td>
                                        <td>{{setting('site.bank_account_number')}}</td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Address and Contact ends -->

                    <!-- Invoice Description starts -->
                    <div class="table-responsive">
                        <table class="table">
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

                    <div class="card-body invoice-padding pb-0">
                        <div class="row invoice-sales-total-wrapper">
                            <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                                <p class="card-text mb-0">
                                    <span class="fw-bold">Salesperson:</span> <span class="ms-75">{{$customerLaundry->author->name}}</span>
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
                    </div>
                    <!-- Invoice Description ends -->

                    <hr class="invoice-spacing" />

                    <!-- Invoice Note starts -->
                    <div class="card-body invoice-padding pt-0">
                        <div class="row">
                            <div class="col-12">
                                <span class="fw-bold">Note:</span>
                                <span
                                >{{$customerLaundry->note}}</span
                                >
                            </div>
                        </div>
                    </div>
                    <!-- Invoice Note ends -->
                    <hr class="invoice-spacing" />

                        <h4 >Payments</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="py-1">Amount</th>
                                <th class="py-1">Received By</th>
                                <th class="py-1">Received On</th>
                                <th class="py-1">Payment Mode</th>
                                <th class="py-1">Balance Before Payment</th>
                                <th class="py-1">Balance After Payment</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customerLaundry->customer_laundry_payments as $lp)
                                <tr class="@if($loop->last) border-bottom @endif">
                                    <td class="py-1">
                                        <p class="card-text fw-bold mb-25">&#8358;{{$lp->amount_paid}}</p>

                                    </td>
                                    <td class="py-1 ">
                                        <span class="fw-bold">{{$lp->author->name}}</span>
                                    </td>
                                    <td class="py-1">
                                        <span class="fw-bold">{{$lp->date}}</span>
                                    </td>
                                    <td class="py-1">
                                        <span class="fw-bold">{{$lp->payment_type}}</span>
                                    </td>
                                    <td class="py-1">
                                        <span class="fw-bold">&#8358;{{$lp->previous_debt}}</span>
                                    </td>
                                    <td class="py-1">
                                        <span class="fw-bold">&#8358;{{$lp->debt_after_payment}}</span>
                                    </td>
                                    <td class="py-1">
                                        <span class="fw-bold"><a target="_blank" href="{{url('laundries/receipt/'.$lp->id)}}" class="btn btn-primary btn-sm">Print Receipt</a></span>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- /Invoice -->

            <!-- Invoice Actions -->
            <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-primary w-100 mb-75" data-bs-toggle="modal" data-bs-target="#send-invoice-sidebar">
                            Send Invoice
                        </button>
                        <button class="btn btn-outline-secondary w-100 btn-download-invoice mb-75">Download</button>
                        <a class="btn btn-outline-secondary w-100 mb-75" href="{{url('laundries/print/'.$customerLaundry->id)}}" target="_blank"> Print </a>
                        <a class="btn btn-outline-secondary w-100 mb-75" href="{{url('laundries/thermal_print/'.$customerLaundry->id)}}" target="_blank">Thermal Print </a>
                        <a class="btn btn-outline-secondary w-100 mb-75" href="{{url('app/invoice/edit')}}"> Edit </a>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" onclick="returnLaundry();">
                           Return Laundry
                        </button>
                        @if($customerLaundry->payment_status==0)
                        <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#add-payment-sidebar">
                            Add Payment
                        </button>
                            @endif
                    </div>
                </div>
            </div>
            <!-- /Invoice Actions -->
        </div>
    </section>

    <!-- Send Invoice Sidebar -->
    <div class="modal modal-slide-in fade" id="send-invoice-sidebar" aria-hidden="true">
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">
                        <span class="align-middle">Send Invoice</span>
                    </h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <form>
                        <div class="mb-1">
                            <label for="invoice-from" class="form-label">From</label>
                            <input
                                type="text"
                                class="form-control"
                                id="invoice-from"
                                value="shelbyComapny@email.com"
                                placeholder="company@email.com"
                            />
                        </div>
                        <div class="mb-1">
                            <label for="invoice-to" class="form-label">To</label>
                            <input
                                type="text"
                                class="form-control"
                                id="invoice-to"
                                value="qConsolidated@email.com"
                                placeholder="company@email.com"
                            />
                        </div>
                        <div class="mb-1">
                            <label for="invoice-subject" class="form-label">Subject</label>
                            <input
                                type="text"
                                class="form-control"
                                id="invoice-subject"
                                value="Invoice of purchased Admin Templates"
                                placeholder="Invoice regarding goods"
                            />
                        </div>
                        <div class="mb-1">
                            <label for="invoice-message" class="form-label">Message</label>
                            <textarea
                                class="form-control"
                                name="invoice-message"
                                id="invoice-message"
                                cols="3"
                                rows="11"
                                placeholder="Message..."
                            >
Dear Queen Consolidated,

Thank you for your business, always a pleasure to work with you!

We have generated a new invoice in the amount of $95.59

We would appreciate payment of this invoice by 05/11/2019</textarea
                            >
                        </div>
                        <div class="mb-1">
            <span class="badge badge-light-primary">
              <i data-feather="link" class="me-25"></i>
              <span class="align-middle">Invoice Attached</span>
            </span>
                        </div>
                        <div class="mb-1 d-flex flex-wrap mt-2">
                            <button type="button" class="btn btn-primary me-1" data-bs-dismiss="modal">Send</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Send Invoice Sidebar -->

    <!-- Add Payment Sidebar -->
    <div class="modal modal-slide-in fade" id="add-payment-sidebar" aria-hidden="true">
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">
                        <span class="align-middle">Add Payment</span>
                    </h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <form id="paymentForm" method="POST" onsubmit="event.preventDefault(); paymentFormSubmit();">
                        <div class="mb-1">
                            <input id="balance" class="form-control" type="text" value="Invoice Balance: {{$customerLaundry->total_after_tax-$customerLaundry->total_amount_paid}}" disabled />
                        </div>
                        <input type="hidden" name="customer_laundry_id" value="{{$customerLaundry->id}}">
                        <div class="mb-1">
                            <label class="form-label" for="amount">Payment Amount</label>
                            <input id="amount" required name="amount" step=".01" class="form-control" type="number" placeholder="" />
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="payment-date">Payment Date</label>
                            <input id="payment-date" required name="received_date" class="form-control date-picker" type="text" />
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="payment-method">Payment Method</label>
                            <select class="form-select" required  id="payment-method" name="payment_mode">
                                <option value="" selected disabled>Select payment method</option>
                                <option value="1">Bank Transfer</option>
                                <option value="2">Debit Card</option>
                            </select>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="payment-note">Internal Payment Note</label>
                            <textarea class="form-control" name="description" id="payment-note" rows="5" placeholder="Internal Payment Note"></textarea>
                        </div>
                        <div class="d-flex flex-wrap mb-0">
                            <button type="submit" class="btn btn-primary me-1" >Send</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Payment Sidebar -->
@endsection

@section('vendor-script')
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>

    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>

@endsection

@section('page-script')
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
{{--    <script src="{{asset('js/scripts/pages/app-invoice.js')}}"></script>--}}
    <script >
        $(function () {
            'use strict';
            var date = new Date();
            var datepicker = $('.date-picker')
            if (datepicker.length) {
                datepicker.each(function () {
                    $(this).flatpickr({
                        defaultDate: date
                    });
                });
            }
        });
        function returnLaundry(){
            var date2 = new Date();
            Swal.fire({
                title: "Please select the date Laundry was returned to customer",
                html:'<input id="returndate" class="form-control" autofocus>',
                type: "warning",
                didOpen: function() {
                    $('#returndate').flatpickr({
                        defaultDate: date2
                    });
                }
            }).then(function(result) {
                if(result.value){
                    alert(	$('#returndate').val()	);
                }
            });
        }
        function paymentFormSubmit(){

            if(parseFloat($('#amount').val())>{{$customerLaundry->total_after_tax-$customerLaundry->total_amount_paid}}){
                Swal.fire({
                    title: 'Error',
                    text: 'Amount paid cannot be more that amount owed',
                    icon: 'danger',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });
                return;
            }
            submitForm('paymentForm','{{route('payment.save')}}','progress','{{url('laundries/receipt')}}','reload');
        };
        function submitForm(formid, url, progress, reloadUrl, reload = false) {
            formdata = new FormData($('#' + formid)[0]);
            formdata.append('_token', '{{csrf_token()}}');
            $('button').attr('disabled', true);

            return $.ajax({
                url: url,
                type: 'POST',
                data: formdata,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data, status, xhr) {

                    $('button').attr('disabled', false);

                    // alert('d');x
                    if (data.status == 'success') {



                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                            setTimeout(function () {
                                window.location = reloadUrl+'/'+data.id;
                            }, 2000);
                            return;




                    }else if(data.status == 'error'){

                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'danger',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });

                        return;
                    }else if (reload == 'no_reload') {

                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        toastr.success(data.message);
                    } else if (reloadUrl != 0) {

                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });



                    }



                    swal('error', data.message, 'error');
                    if (reload && reload!=='no_reload') {
                        // window.location.reload();
                    }
                    return toastr.error(data.message);
                },
                xhr: function () {
                    var myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
// For handling the progress of the upload
                        myXhr.upload.addEventListener('progress', function (e) {
                            if (e.lengthComputable) {
                                percent = Math.round((e.loaded / e.total) * 100, 2);
                                $('#' + progress).css('width', percent + '%');
                                $('#' + progress + '_text').text(percent + '%');
                            }
                        }, false);
                    }
                    return myXhr;
                }
            });

        }
    </script>
@endsection
