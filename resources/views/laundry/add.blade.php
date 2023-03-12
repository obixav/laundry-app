@extends('layouts/contentLayoutMaster')

@section('title', 'Invoice Edit')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection
@section('page-style')
    <link rel="stylesheet" href="{{asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
    <link rel="stylesheet" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice.css')}}">
@endsection

@section('content')
    @php
        $subtotal=number_format($items->first()->price,2);
        $tax_rate=setting('site.uses_tax')?setting('site.tax_rate'):0;

        $tax=setting('site.uses_tax')?number_format($items->first()->price*($tax_rate/100),2):0;
        $total=number_format($subtotal+$tax,2);
    @endphp
    <section class="invoice-add-wrapper">
        <div class="row invoice-add">

            <!-- Invoice Add Left starts -->
            <div class="col-xl-9 col-md-8 col-12">
                <form  id="customerLaundryForm" method="POST" onsubmit="event.preventDefault(); laundryFormSubmit();">
                <div class="card invoice-preview-card">
                    <!-- Header starts -->
                    <div class="card-body invoice-padding pb-0">
                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                            <div>
                                <div class="logo-wrapper">
                                    <img src="{{asset('images/logo/logo.png')}}" style="height:100px ">
                                    <h3 class="text-primary invoice-logo">{{setting('site.title')}}</h3>
                                </div>
                                <p class="card-text mb-25">{!! setting('site.office_address') !!} </p>
                                <p class="card-text mb-0">{{setting('site.office_phone')}}</p>
                            </div>
                            <div class="invoice-number-date mt-md-0 mt-2">
                                <div class="d-flex align-items-center justify-content-md-end mb-1">
                                    <h4 class="invoice-title">Invoice</h4>
                                    <div class="input-group input-group-merge invoice-edit-input-group">
                                        <div class="input-group-text">
                                            <i data-feather="hash"></i>
                                        </div>
                                        <input type="text" class="form-control invoice-edit-input" placeholder="53634" />
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <span class="title">Date:</span>
                                    <input type="text" name="date_received" required class="form-control invoice-edit-input date-picker" />
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="title">Due Date:</span>
                                    <input type="text" name="due_date" required class="form-control invoice-edit-input due-date-picker" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Header ends -->

                    <hr class="invoice-spacing" />

                    <!-- Address and Contact starts -->
                    <div class="card-body invoice-padding pt-0">
                        <div class="row row-bill-to invoice-spacing">
                            <div class="col-xl-8 mb-lg-1 col-bill-to ps-0">
                                <h6 class="invoice-to-title">Invoice To:</h6>
                                <div class="invoice-customer">
                                    <select name="customer_id" required class="invoiceto form-select">
                                        <option></option>
                                        <option value="shelby">Shelby Company Limited</option>
                                        <option value="hunters">Hunters Corp</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 p-0 ps-xl-2 mt-xl-0 mt-2">
                                <h6 class="mb-2">Payment Details:</h6>
                                <table>
                                    <tbody>
                                    <tr>
                                        <td class="pe-1">Total Due:</td>
                                        <td><strong><span id="total_top">&#8358;{{$total}}</span></strong></td>
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

                    <!-- Product Details starts -->
                    <div class="card-body invoice-padding invoice-product-details">
                        <div class="source-item" >
                            <div data-repeater-list="group-a">
                                <div class="repeater-wrapper" data-repeater-item>
                                    <div class="row">
                                        <div class="col-12 d-flex product-details-border position-relative pe-0">
                                            <div class="row w-100 pe-lg-0 pe-1 py-2">
                                                <div class="col-lg-5 col-12 mb-lg-0 mb-2 mt-lg-0 mt-2">
                                                    <p class="card-text col-title mb-md-50 mb-0">Item</p>
                                                    <select name="item_id" class="form-select item-details">
                                                        @foreach($items as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                    </select>
                                                    <label class="mt-2">Description</label>
                                                    <textarea class="form-control " name="description" required rows="1">Customization & Bug Fixes</textarea>
                                                </div>

                                                <div class="col-lg-3 col-12 my-lg-0 my-2">
                                                    <p class="card-text col-title mb-md-2 mb-0">Cost</p>
                                                    <input type="text" name="price" readonly class="form-control cost_text" value="{{$items->first()->price}}" placeholder="24" />
                                                    <div class="mt-2">
                                                        <span>Discount:</span>
                                                        <span class="discount">0%</span>
                                                        <span class="tax-1 ms-50" data-bs-toggle="tooltip" data-bs-placement="top" title="Tax 1"
                                                        >0%</span
                                                        >
                                                        <span class="tax-2 ms-50" data-bs-toggle="tooltip" data-bs-placement="top" title="Tax 2"
                                                        >0%</span
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-12 my-lg-0 my-2">
                                                    <p class="card-text col-title mb-md-2 mb-0">Qty</p>
                                                    <input type="number" class="form-control qty_text" name="quantity" min="1" value="1" placeholder="1" />
                                                    <label class="mt-2">Discount %</label>
                                                    <input type="number"  class="form-control discount_text " name="discount"  value="0" placeholder="0" />
                                                </div>
                                                <div class="col-lg-2 col-12 mt-lg-0 mt-2">
                                                    <p class="card-text col-title mb-md-50 mb-0">Price</p>
                                                    <input disabled type="number" class="form-control sub-total" name="sub_total" value="{{number_format($items->first()->price,2)}}" placeholder="1" />
                                                    <label class="mt-2">After Discount</label>
                                                    <input type="number" disabled class="form-control after_discount " name="after_discount" value="0" placeholder="0" />
                                                </div>
                                            </div>
                                            <div
                                                class="
                        d-flex
                        flex-column
                        align-items-center
                        justify-content-between
                        border-start
                        invoice-product-actions
                        py-50
                        px-25
                      "
                                            >
                                                <i data-feather="x" class="cursor-pointer font-medium-3" data-repeater-delete></i>
                                                <div class="dropdown">
                                                    <i
                                                        class="cursor-pointer more-options-dropdown me-0"
                                                        data-feather="settings"
                                                        id="dropdownMenuButton"
                                                        role="button"
                                                        data-bs-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false"
                                                    >
                                                    </i>
                                                    <div
                                                        class="dropdown-menu dropdown-menu-end item-options-menu p-50"
                                                        aria-labelledby="dropdownMenuButton"
                                                    >
                                                        <div class="mb-1">
                                                            <label for="discount-input" class="form-label">Discount(%)</label>
                                                            <input type="number" class="form-control sub-discount" id="discount-input" />
                                                        </div>
                                                        <div class="form-row mt-50">
                                                            <div class="mb-1 col-md-6">
                                                                <label for="tax-1-input" class="form-label">Tax 1</label>
                                                                <select name="tax-1-input" id="tax-1-input" class="form-select tax-select">
                                                                    <option value="0%" selected>0%</option>
                                                                    <option value="1%">1%</option>
                                                                    <option value="10%">10%</option>
                                                                    <option value="18%">18%</option>
                                                                    <option value="40%">40%</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-1 col-md-6">
                                                                <label for="tax-2-input" class="form-label">Tax 2</label>
                                                                <select name="tax-2-input" id="tax-2-input" class="form-select tax-select">
                                                                    <option value="0%" selected>0%</option>
                                                                    <option value="1%">1%</option>
                                                                    <option value="10%">10%</option>
                                                                    <option value="18%">18%</option>
                                                                    <option value="40%">40%</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown-divider my-1"></div>
                                                        <div class="d-flex justify-content-between">
                                                            <button type="button" class="btn btn-outline-primary btn-apply-changes">Apply</button>
                                                            <button type="button" class="btn btn-outline-secondary">Cancel</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 px-0">
                                    <button type="button" class="btn btn-primary btn-sm btn-add-new" data-repeater-create>
                                        <i data-feather="plus" class="me-25"></i>
                                        <span class="align-middle">Add Item</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product Details ends -->

                    <!-- Invoice Total starts -->
                    <div class="card-body invoice-padding">
                        <div class="row invoice-sales-total-wrapper">
                            <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                                <div class="d-flex align-items-center mb-1">
                                    <label for="salesperson" class="form-label">Salesperson:</label>
                                    <input type="text" class="form-control ms-50" disabled id="salesperson" value="{{Auth::user()->name}}" placeholder="Edward Crowley" />
                                </div>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">

                                <div class="invoice-total-wrapper">
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title" >Subtotal:</p>
                                        <p class="invoice-total-amount"id="total-before-discount">&#8358;{{$subtotal}}</p>
                                    </div>
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Discount:</p>
                                        <p class="invoice-total-amount" id="discount">&#8358;0</p>
                                    </div>
                                    <hr class="my-50" />
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">After Discount:</p>
                                        <p class="invoice-total-amount" id="after_discount">&#8358;{{$subtotal}}</p>
                                    </div>
                                    <hr class="my-50" />
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Tax Rate:</p>
                                        <p class="invoice-total-amount" id="total-before-discount">{{$tax_rate}}%</p>
                                    </div>
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Tax:</p>
                                        <p class="invoice-total-amount" id="tax">&#8358;{{$tax}}</p>
                                    </div>

                                    <hr class="my-50" />
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Total:</p>
                                        <p class="invoice-total-amount" id="after_tax">&#8358;{{$total}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Invoice Total ends -->

                    <hr class="invoice-spacing mt-0" />

                    <div class="card-body invoice-padding py-0">
                        <!-- Invoice Note starts -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="note" class="form-label fw-bold">Note:</label>
                                    <textarea class="form-control" rows="2" id="note" name="note"></textarea
                                    >
                                </div>
                            </div>
                        </div>
                        <!-- Invoice Note ends -->
                    </div>
                    <hr class="invoice-spacing mt-0" />
                    <div class="row mt-1">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg   m-2" data-repeater-create>
                                <span class="align-middle">Save</span>
                            </button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
            <!-- Invoice Add Left ends -->

            <!-- Invoice Add Right starts -->
            <div class="col-xl-3 col-md-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-primary w-100 mb-75" disabled>Send Invoice</button>
                        <a href="{{url('app/invoice/preview')}}" class="btn btn-outline-primary w-100 mb-75">Preview</a>
                        <button type="button" class="btn btn-outline-primary w-100" onclick="event.preventDefault(); document.getElementById('customerLaundryForm').submit();">Save</button>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="mb-50">Accept payments via</p>
                    <select class="form-select">
                        <option value="Bank Account">Bank Account</option>
                        <option value="Paypal">Paypal</option>
                        <option value="UPI Transfer">UPI Transfer</option>
                    </select>
                    <div class="invoice-terms mt-1">
                        <div class="d-flex justify-content-between">
                            <label class="invoice-terms-title mb-0" for="paymentTerms">Payment Terms</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" checked id="paymentTerms" />
                                <label class="form-check-label" for="paymentTerms"></label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <label class="invoice-terms-title mb-0" for="clientNotes">Client Notes</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" checked id="clientNotes" />
                                <label class="form-check-label" for="clientNotes"></label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <label class="invoice-terms-title mb-0" for="paymentStub">Payment Stub</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="paymentStub" />
                                <label class="form-check-label" for="paymentStub"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Invoice Add Right ends -->

        </div>

        <!-- Add New Customer Sidebar -->
        <div class="modal modal-slide-in fade" id="add-new-customer-sidebar" aria-hidden="true">
            <div class="modal-dialog sidebar-lg">
                <div class="modal-content p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title">
                            <span class="align-middle">Add Customer</span>
                        </h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <form id="addCustomerForm"  method="POST" onsubmit="event.preventDefault(); customerFormSubmit();">
                            <div class="mb-1">
                                <label for="customer-name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" name="name" required id="customer-name" placeholder="John Doe" />
                            </div>

                            <div class="mb-1">
                                <label for="customer-address" class="form-label">Customer Address</label>
                                <textarea
                                    class="form-control"
                                    id="customer-address"
                                    cols="2"
                                    rows="2"
                                    name="address"
                                    placeholder="1307 Lady Bug Drive New York"
                                ></textarea>
                            </div>
                            <div class="mb-1">
                                <label for="customer-contact" class="form-label">Phone</label>
                                <input type="number" required class="form-control" name="phone" id="customer-contact" placeholder="081-2345-6789" />
                            </div>
                            <div class="mb-1 d-flex flex-wrap mt-2">
                                <button type="submit" class="btn btn-primary me-1">Add</button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add New Customer Sidebar -->
    </section>
@endsection

@section('vendor-script')
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{asset('vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>

@endsection

@section('page-script')
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
    @include('laundry.script')
@endsection
