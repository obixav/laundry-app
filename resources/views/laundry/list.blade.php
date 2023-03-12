@extends('layouts/contentLayoutMaster')

@section('title', 'Invoice List')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice-list.css')}}">
@endsection

@section('content')
    <section class="invoice-list-wrapper">
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
                </table>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')
    <script src="{{asset('vendors/js/extensions/moment.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/datatables.buttons.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
@endsection

@section('page-script')
{{--    <script src="{{asset('js/scripts/pages/app-invoice-list.js')}}"></script>--}}
    <script>
        $(document).ready(function () {
            var invoiceAdd='{{route('laundry.create')}}',
            invoicePreview = '{{url('laundry')}}';
            $('#invoice_list').DataTable({
                serverSide: true,
                ajax: {
                    url: '{{url('laundries/fetch')}}',
                    dataFilter: function(data){

                        var json = jQuery.parseJSON( data );
                        console.log(json.data)
                        json.recordsTotal = json.total;
                        json.recordsFiltered = json.total;
                        json.data = json.data;

                        return JSON.stringify( json ); // return JSON string
                    }
                },
                columns: [
                    {data:'id'},
                    { data: 'customer.name' },
                    { data: 'after_tax' },
                    { data: 'amount_paid' },
                    { data: 'balance' },
                    { data: 'date_received' },
                    { data: 'payment_status' },
                    { data: 'return_status' },
                ],
                columnDefs:[
                    {
                        targets:1,
                        orderable:false
                    },
                    {targets:4,
                        orderable: false,
                        render: function (data, type, full, meta) {
                            var $balance = full['balance'];
                            if ($balance === 0) {
                                var $badge_class = 'badge-light-success';
                                return '<span class="badge rounded-pill ' + $badge_class + '" text-capitalized> Paid </span>';
                            } else {
                                return '<span class="d-none">' + $balance + '</span>' + $balance;
                            }
                        }
                    },
                    {targets:6,
                        render: function (data, type, full, meta) {
                            var $returned = full['return_status'];
                            if ($returned === 1) {
                                var $badge_class = 'badge-light-success';
                                return '<span class="badge rounded-pill ' + $badge_class + '" text-capitalized> Returned </span>';
                            } else {
                                var $badge_class = 'badge-light-warning';
                                return '<span class="badge rounded-pill ' + $badge_class + '" text-capitalized> Pending </span>';
                            }
                        }
                    }, {
                        // Actions
                        targets: 7,
                        title: 'Actions',
                        width: '80px',
                        orderable: false,
                        render: function (data, type, full, meta) {
                            return (
                                '<div class="d-flex align-items-center col-actions">' +

                                '<a class="me-25" href="' +
                                invoicePreview +'/'+ full['id']+
                                '" data-bs-toggle="tooltip" data-bs-placement="top" title="Preview Invoice">' +
                                feather.icons['eye'].toSvg({ class: 'font-medium-2 text-body' }) +
                                '</a>' +
                                '</div>'
                            );
                        }
                    }],
                buttons: [
                    {
                        text: 'Add Record',
                        className: 'btn btn-primary btn-add-record ms-2',
                        action: function (e, dt, button, config) {
                            window.location = invoiceAdd;
                        }
                    }
                ],
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
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
