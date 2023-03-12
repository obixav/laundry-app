<script>
    /*=========================================================================================
    File Name: app-invoice.js
    Description: app-invoice Javascripts
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
   Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/
    $(function () {
        'use strict';

        function getcustomers() {
            return fetch('{{url('customers')}}')
                .then((response) => response.json())
                .then((data) => {
                    console.log(data[0])
                    var renderDetails =
                        '<div class="customer-details mt-1">' +
                        '<p class="mb-25">' +
                        data[0].name +
                        '</p>' +
                        '<p class="mb-25">' +
                        data[0].phone +
                        '</p>' +
                        '<p class="mb-25">' +
                        data[0].address +
                        '</p>' +
                        '</div>';
                    $('.row-bill-to').find('.customer-details').remove();
                    $('.row-bill-to').find('.col-bill-to').append(renderDetails);
                });
        }


        var applyChangesBtn = $('.btn-apply-changes'),
            discount,
            tax1,
            tax2,
            discountInput,
            tax1Input,
            tax2Input,
            sourceItem = $('.source-item'),
            date = new Date(),
            datepicker = $('.date-picker'),
            dueDate = $('.due-date-picker'),
            select2 = $('.invoiceto'),
            countrySelect = $('#customer-country'),
            btnAddNewItem = $('.btn-add-new '),
            adminDetails = {
                'App Design': 'Designed UI kit & app pages.',
                'App Customization': 'Customization & Bug Fixes.',
                'ABC Template': 'Bootstrap 4 admin template.',
                'App Development': 'Native App Development.'
            },
            customerDetails = {
                shelby: {
                    name: 'Thomas Shelby',
                    company: 'Shelby Company Limited',
                    address: 'Small Heath, Birmingham',
                    pincode: 'B10 0HF',
                    country: 'UK',
                    tel: 'Tel: 718-986-6062',
                    email: 'peakyFBlinders@gmail.com'
                },
                hunters: {
                    name: 'Dean Winchester',
                    company: 'Hunters Corp',
                    address: '951  Red Hawk Road Minnesota,',
                    pincode: '56222',
                    country: 'USA',
                    tel: 'Tel: 763-242-9206',
                    email: 'waywardSon@gmail.com'
                }
            };

        // init date picker
        if (datepicker.length) {
            datepicker.each(function () {
                $(this).flatpickr({
                    defaultDate: date
                });
            });
        }

        if (dueDate.length) {
            dueDate.flatpickr({
                defaultDate: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 3)
            });
        }

        // Country Select2
        if (countrySelect.length) {
            countrySelect.select2({
                placeholder: 'Select country',
                dropdownParent: countrySelect.parent()
            });
        }

        // Close select2 on modal open
        $(document).on('click', '.add-new-customer', function () {
            select2.select2('close');
        });

        var url = "{{url('customers')}}";
        if (select2.length) {
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

            select2.on('change', function () {
                var $this = $(this);

                fetch('{{url('customers')}}' + '/' + $this.val())
                    .then((response) => response.json())
                    .then((data) => {
                        var renderDetails =
                            '<div class="customer-details mt-1">' +
                            '<p class="mb-25">' +
                            data.name +
                            '</p>' +
                            '<p class="mb-25">' +
                            data.phone +
                            '</p>' +
                            '<p class="mb-25">' +
                            data.address +
                            '</p>' +
                            '</div>';
                        $('#customer_id').val(data.id);
                        $('.row-bill-to').find('.customer-details').remove();
                        $('.row-bill-to').find('.col-bill-to').append(renderDetails);
                    });
            });

            select2.on('select2:open', function () {
                if (!$(document).find('.add-new-customer').length) {
                    $(document)
                        .find('.select2-results__options')
                        .before(
                            '<div class="add-new-customer btn btn-flat-success cursor-pointer rounded-0 text-start mb-50 p-50 w-100" data-bs-toggle="modal" data-bs-target="#add-new-customer-sidebar">' +
                            feather.icons['plus'].toSvg({class: 'font-medium-1 me-50'}) +
                            '<span class="align-middle">Add New Customer</span></div>'
                        );
                }
            });
        }

        // Repeater init
        if (sourceItem.length) {
            sourceItem.on('submit', function (e) {
                e.preventDefault();
            });
            sourceItem.repeater({
                show: function () {
                    $(this).slideDown();
                },
                hide: function (e) {
                    $(this).slideUp();
                }
            });
        }

        // Prevent dropdown from closing on tax change
        $(document).on('click', '.tax-select', function (e) {
            e.stopPropagation();
        });

        // On tax change update it's value
        function updateValue(listener, el) {
            listener.closest('.repeater-wrapper').find(el).text(listener.val());
        }

        $('#addCustomerForm').submit(function (e) {
            e.preventDefault();
            submitForm('addCustomerForm', '{{url('customers')}}', 'progress', '{{request()->fullUrl()}}', false);
        });

        {{--function submitForm(formid, url, progress, reloadUrl, reload = false) {--}}
        {{--    var formdata = new FormData($('#' + formid)[0]);--}}
        {{--    formdata.append('_token', '{{csrf_token()}}');--}}

        {{--    $('button').attr('disabled', true);--}}

        {{--    return $.ajax({--}}
        {{--        url: url,--}}
        {{--        type: 'POST',--}}
        {{--        data: formdata,--}}
        {{--        cache: false,--}}
        {{--        contentType: false,--}}
        {{--        processData: false,--}}
        {{--        success: function (data, status, xhr) {--}}

        {{--            $('button').attr('disabled', false);--}}

        {{--            // alert('d');x--}}


        {{--            if (reload && reload !== 'no_reload') {--}}
        {{--                window.location.reload();--}}
        {{--            }--}}

        {{--        }--}}
        {{--    });--}}

        {{--}--}}


        // Apply item changes btn
        if (applyChangesBtn.length) {
            $(document).on('click', '.btn-apply-changes', function (e) {
                var $this = $(this);
                tax1Input = $this.closest('.dropdown-menu').find('#tax-1-input');
                tax2Input = $this.closest('.dropdown-menu').find('#tax-2-input');
                discountInput = $this.closest('.dropdown-menu').find('#discount-input');
                tax1 = $this.closest('.repeater-wrapper').find('.tax-1');
                tax2 = $this.closest('.repeater-wrapper').find('.tax-2');
                discount = $('.discount');

                if (tax1Input.val() !== null) {
                    updateValue(tax1Input, tax1);
                }

                if (tax2Input.val() !== null) {
                    updateValue(tax2Input, tax2);
                }

                if (discountInput.val().length) {
                    var finalValue = discountInput.val() <= 100 ? discountInput.val() : 100;
                    $this
                        .closest('.repeater-wrapper')
                        .find(discount)
                        .text(finalValue + '%');
                }
            });
        }

        // Item details select onchange
        $(document).on('change', '.item-details', function () {
            var $this = $(this),
                value = adminDetails[$this.val()];
        //     if ($this.next('textarea').length) {
        //         $this.next('textarea').val(value);
        //     } else {
        //         $this.after('<textarea class="form-control mt-2" rows="2">' + value + '</textarea>');
        //     }
        });
        if (btnAddNewItem.length) {
            btnAddNewItem.on('click', function () {
                if (feather) {
                    // featherSVG();
                    feather.replace({width: 14, height: 14});
                }
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        }


        $(document).on('change', '.item-details', function () {
            var $this = $(this);

            fetch('{{url('items')}}' + '/' + $this.val())
                .then((response) => response.json())
                .then((data) => {
                    var cost=data.price;
                    $this.closest('.repeater-wrapper').find(".cost_text").val(data.price)
                    var qty=$this.closest('.repeater-wrapper').find(".qty_text").val()
                    var discount=$this.closest('.repeater-wrapper').find(".discount_text").val()
                    var sub_total = parseInt(qty) * parseInt(cost);
                    var after_discount=(parseInt(qty) * parseInt(cost))-((parseInt(qty) * parseInt(cost))*(discount/100))
                    $this.closest('.repeater-wrapper').find(".sub-total").val(parseFloat(data.price).toFixed(2))
                    $this.closest('.repeater-wrapper').find(".after_discount").val(parseFloat(after_discount).toFixed(2))
                    calculateSum()
                });
        });
        $(document).on('keyup', '.qty_text', function () {
            var $this = $(this);
var qty=$this.val();
            if ($this.val() < 1) {
                qty=1;
                $this.val(1);
            }
                var cost = $this.closest('.repeater-wrapper').find(".cost_text").val()
            var discount=$this.closest('.repeater-wrapper').find(".discount_text").val()
                var sub_total = parseInt(qty) * parseInt(cost);
                var after_discount=(parseInt(qty) * parseInt(cost))-((parseInt(qty) * parseInt(cost))*(discount/100))
                $this.closest('.repeater-wrapper').find(".sub-total").val(parseFloat(sub_total).toFixed(2))
            $this.closest('.repeater-wrapper').find(".after_discount").val(parseFloat(after_discount).toFixed(2))
                calculateSum()


        });
        $(document).on('keyup', '.discount_text', function () {
            var $this = $(this);
            var qty=$this.closest('.repeater-wrapper').find(".qty_text").val()
            var cost = $this.closest('.repeater-wrapper').find(".cost_text").val()
            var discount=$this.closest('.repeater-wrapper').find(".discount_text").val()
            var sub_total = parseInt(qty) * parseInt(cost);
            var after_discount=(parseInt(qty) * parseInt(cost))-((parseInt(qty) * parseInt(cost))*(discount/100))
            $this.closest('.repeater-wrapper').find(".sub-total").val(parseFloat(sub_total).toFixed(2))
            $this.closest('.repeater-wrapper').find(".after_discount").val(parseFloat(after_discount).toFixed(2))
            calculateSum()
        });

        function calculateSum() {
            var sum = 0;
            var after_discount_sum=0
            var tax=0;
            var tax_rate=0;
            var after_tax=0;

            $(".sub-total").each(function () {
                if (!isNaN(this.value) && this.value.length != 0) {
                    sum += parseFloat(this.value);
                }
            });
            $(".after_discount").each(function () {
                if (!isNaN(this.value) && this.value.length != 0) {
                    after_discount_sum += parseFloat(this.value);
                }
            });
            @if(setting('site.uses_tax')==1)
                var tax_rate=parseFloat({{setting('site.tax_rate')}})

            @endif
            console.log(after_discount_sum)
            tax=(after_discount_sum*(tax_rate/100))
            discount=sum-after_discount_sum

            after_tax=after_discount_sum+tax

            $("#total-before-discount").html('&#8358;' + sum.toFixed(2));
            $("#after_discount").html('&#8358;' + after_discount_sum.toFixed(2));
            $("#discount").html('&#8358;' + discount.toFixed(2));
            $("#tax").html('&#8358;' + tax.toFixed(2));
            $("#after_tax").html('&#8358;' + after_tax.toFixed(2));
            $("#total_top").html('&#8358;' + after_tax.toFixed(2));
        }






    });
    function customerFormSubmit(){


            submitForm('addCustomerForm', '{{url('customers')}}', 'progress', 'no_reload_url', 'no_reload');
            $("#add-new-customer-sidebar").hide();

    }
    function laundryFormSubmit(){
        Swal.fire({
            title: 'Good job!',
            text: 'You clicked the button!',
            icon: 'success',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });

        submitForm('customerLaundryForm','{{route('laundry.store')}}','progress','{{route('laundry.index')}}','reload');
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
console.log(data)
                // alert('d');x
                if (data.status == 'success') {


                    if (reloadUrl == 'no_reload_url') {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });

                        return;
                    }



                } else if (reload == 'no_reload') {

                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                    // toastr.success(data.message);
                return
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

                    setTimeout(function () {
                        window.location = reloadUrl;
                    }, 2000);

                }



                swal('error', data.message, 'error');
                if (reload && reload!=='no_reload') {
                    window.location.reload();
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
