@extends('layouts/contentLayoutMaster')

@section('title', 'Transaction Report')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
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
                            <div id="accordionOne" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-12">

                                                    <form class="form">
                                                        <div class="row">
                                                            <div class="col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="last-name-column">From</label>
                                                                    <input type="text" id="from" class="form-control flatpickr-human-friendly"  name="from" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-12">
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

        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card card-statistics">
                    <div class="card-header">
                        <h4 class="card-title">Statistics</h4>
                        <div class="d-flex align-items-center">
                            <p class="card-text me-25 mb-0">{{request()->from==''?'This Month':date('F j, Y',strtotime(request()->from)).' - '.date('F j, Y',strtotime(request()->to))}}</p>
                        </div>
                    </div>
                    <div class="card-body statistics-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-primary me-2">
                                        <div class="avatar-content">
                                            <i data-feather="box" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{$totalItemsWashed}}</h4>
                                        <p class="card-text font-small-3 mb-0">Items Washed</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-info me-2">
                                        <div class="avatar-content">
                                            <i data-feather="user" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{$newCustomers}}</h4>
                                        <p class="card-text font-small-3 mb-0">New Customers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-primary me-2">
                                        <div class="avatar-content">
                                            <i data-feather="trending-up" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">&#8358;{{$totalAmountMade}}</h4>
                                        <p class="card-text font-small-3 mb-0">Expected Revenue</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-success me-2">
                                        <div class="avatar-content">
                                            <i data-feather="dollar-sign" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">&#8358;{{$totalActualAmountMade}}</h4>
                                        <p class="card-text font-small-3 mb-0">Actual Revenue</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="row">
        <!-- Transaction card -->
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card card-transaction">
                <div class="card-header">
                    <h4 class="card-title">Top 5 Customers</h4>
                    <i data-feather="more-vertical" class="font-medium-3 cursor-pointer"></i>
                </div>
                <div class="card-body">
                    @foreach($bestCustomers as $bc)
                    <div class="transaction-item">
                        <div class="d-flex flex-row">
                            <div class="avatar bg-light-primary rounded">
                                <div class="avatar-content">
                                    <i data-feather="user" class="avatar-icon font-medium-3"></i>
                                </div>
                            </div>
                            <div class="transaction-info">
                                <h6 class="transaction-title">{{$bc->name}}</h6>
                                <small>{{$bc->phone}}</small>
                            </div>
                        </div>
                        <div class="fw-bolder text-success"> &#8358;{{$bc->sum_totals}}</div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
        <!--/ Transaction card -->
        <!-- Transaction card -->
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card card-transaction">
                <div class="card-header">
                    <h4 class="card-title">Top 5 Customers Volume</h4>
                    <i data-feather="more-vertical" class="font-medium-3 cursor-pointer"></i>
                </div>
                <div class="card-body">
                    @foreach($bestCustomerItems as $bci)
                        <div class="transaction-item">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-primary rounded">
                                    <div class="avatar-content">
                                        <i data-feather="user" class="avatar-icon font-medium-3"></i>
                                    </div>
                                </div>
                                <div class="transaction-info">
                                    <h6 class="transaction-title">{{$bci->name}}</h6>
                                    <small>{{$bci->phone}}</small>
                                </div>
                            </div>
                            <div class="fw-bolder text-success"> {{$bci->sum_quantity}} items</div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <!--/ Transaction card -->
    <!-- Transaction card -->
    <div class="col-lg-4 col-md-6 col-12">
        <div class="card card-transaction">
            <div class="card-header">
                <h4 class="card-title">Top 5 Washed Items</h4>
                <i data-feather="more-vertical" class="font-medium-3 cursor-pointer"></i>
            </div>
            <div class="card-body">
                @foreach($mostWashedItems as $mwi)
                    <div class="transaction-item">
                        <div class="d-flex flex-row">
                            <div class="avatar bg-light-primary rounded">
                                <div class="avatar-content">
                                    <i data-feather="box" class="avatar-icon font-medium-3"></i>
                                </div>
                            </div>
                            <div class="transaction-info">
                                <h6 class="transaction-title">{{$mwi->name}}</h6>
                                <small></small>
                            </div>
                        </div>
                        <div class="fw-bolder text-success"> {{$mwi->sum_quantity}} items</div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <!--/ Transaction card -->
</div>
    </section>
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
{{--    <script src="{{asset('js/scripts/pages/app-invoice-list.js')}}"></script>--}}
    <script>
        $(document).ready(function () {
            var humanFriendlyPickr = $('.flatpickr-human-friendly');
            var to=$('#to');
            var from=$('#from');

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

            if (humanFriendlyPickr.length) {
                humanFriendlyPickr.flatpickr({
                    altInput: true,
                    altFormat: 'F j, Y',
                    dateFormat: 'Y-m-d'
                });
            }

        });
    </script>
@endsection
