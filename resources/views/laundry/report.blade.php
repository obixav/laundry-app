@extends('layouts/contentLayoutMaster')

@section('title', 'Transaction Report')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice-list.css')}}">
@endsection

@section('content')
    <section class="invoice-list-wrapper">

            <div class="card">

                <div class="card-body">

                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                                    Filter
                                </button>
                            </h2>
                            <div id="accordionOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-12">

                                                    <form class="form">
                                                        <div class="row">
                                                            <div class="col-md-4 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="first-name-column">Customer</label>
                                                                    <select name="customer_id" id="customer_id" class=" form-control" >
                                                                        <option value="0">Select Customer</option>
                                                                    </select>
                                                                    </div>
                                                            </div>
                                                            <div class="col-md-4 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="last-name-column">From</label>
                                                                    <input type="text" id="from" class="form-control flatpickr-human-friendly"  name="from" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="city-column">To</label>
                                                                    <input type="text" id="to" class="form-control flatpickr-human-friendly"  name="to" />
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <button type="submit" class="btn btn-primary me-1">Submit</button>
                                                                <button type="reset" class="btn btn-outline-secondary me-1">Reset</button>
                                                                <a href="{{request()->url()}}" class="btn btn-outline-primary">Clear Filters</a>
                                                            </div>
                                                        </div>
                                                    </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <div class="card">
            <div class="card-datatable table-responsive p-2">
                <table class=" table" id="invoice_list">
                    <thead>
                    <tr>

                        <th>#</th>
                        <th>Client</th>
                        <th>Amount</th>
                        <th>Amount Paid</th>
                        <th>Balance</th>
                        <th>Received Date</th>
                        <th>Return Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customerLaundries as $cl)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$cl->customer->name}}</td>
                            <td>{{$cl->total_after_tax}}</td>
                            <td>{{$cl->total_amount_paid}}</td>
                            <td><span class="badge rounded-pill ' + $badge_class + '" text-capitalized> Paid </span>{{$cl->balance}}</td>
                            <td>{{$cl->date_received}}</td>
                            <td><span class="badge rounded-pill ' + {{$cl->return_status==1?'badge-light-success':'badge-light-warning'}} + '" text-capitalized> {{$cl->return_status==1?'Returned':'Pending'}} </span></td>
                            <td>
                                <a class="btn btn-primary" href="{{url('laundry/'.$cl->id)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Preview Invoice">
                                 <i class="feather feather-eye"></i>
                                        </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>

    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
{{--    <script src="{{asset('js/scripts/pages/app-invoice-list.js')}}"></script>--}}
    <script>
        $(document).ready(function () {

            var invoiceAdd='{{route('laundry.create')}}',
            invoicePreview = '{{url('laundry')}}',
                humanFriendlyPickr = $('.flatpickr-human-friendly');
            var select2 = $('#customer_id');
            var to=$('#to');
            var from=$('#from');
            var url = "{{url('customers')}}";
            to.on('change', function () {
               if(from.val()>to.val()){
                   alert('cannot be less');
                   const fp = document.querySelector("#to")._flatpickr;
                   fp.setDate(from.val());
                   fp.set('altInput',true);
                   fp.set('altFormat','F j, Y');
                   // to.flatpickr().set('altInput',true);
                   // to.flatpickr().set('altFormat','F j, Y');


               }
            });
            select2.select2({
                placeholder: 'Select Customer',
                allowClear: true,
                ajax: {
                    delay: 250,
                    processResults: function (data) {

                        return {

                            results: data
                        };
                    },
                    data: function (params) {
                        return {
                            q: params.term,
                            jobid: sessionStorage.getItem('id'),
                            page_limit: 10
                        };
                    },

                    url: function (params) {
                        return url;
                    }

                }
            });
            if (humanFriendlyPickr.length) {
                humanFriendlyPickr.flatpickr({
                    altInput: true,
                    altFormat: 'F j, Y',
                    dateFormat: 'Y-m-d'
                });
            }
            $('#invoice_list').DataTable({

                columnDefs:[
                    {
                        targets:1,
                        orderable:false
                    },
                    {targets:4,
                        orderable: false,

                    },
                     {
                        // Actions
                        targets: 7,
                        orderable: false,

                    }],
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 7,
                lengthMenu: [7, 10, 25, 50, 75, 100],
                buttons: [
                    {
                        extend: 'collection',
                        className: 'btn btn-outline-secondary dropdown-toggle me-2',
                        text: feather.icons['share'].toSvg({ class: 'font-small-4 me-50' }) + 'Export',
                        buttons: [
                            {
                                extend: 'print',
                                text: feather.icons['printer'].toSvg({ class: 'font-small-4 me-50' }) + 'Print',
                                className: 'dropdown-item',
                            },
                            {
                                extend: 'csv',
                                text: feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) + 'Csv',
                                className: 'dropdown-item',
                            },
                            {
                                extend: 'excel',
                                text: feather.icons['file'].toSvg({ class: 'font-small-4 me-50' }) + 'Excel',
                                className: 'dropdown-item',
                            },
                            {
                                extend: 'pdf',
                                text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 me-50' }) + 'Pdf',
                                className: 'dropdown-item',
                            },
                            {
                                extend: 'copy',
                                text: feather.icons['copy'].toSvg({ class: 'font-small-4 me-50' }) + 'Copy',
                                className: 'dropdown-item',
                            }
                        ],
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                            $(node).parent().removeClass('btn-group');
                            setTimeout(function () {
                                $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                            }, 50);
                        }
                    },
                    {
                        text: 'Add Record',
                        className: 'btn btn-primary btn-add-record ms-2',
                        action: function (e, dt, button, config) {
                            window.location = invoiceAdd;
                        }
                    }
                ],
                language: {
                    sLengthMenu: 'Show _MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search Invoice',
                    paginate: {
                        // remove previous & next text from pagination
                        previous: '&nbsp;',
                        next: '&nbsp;'
                    }
                },
            });
        });
    </script>
@endsection
