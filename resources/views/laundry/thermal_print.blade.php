<html>
<head><title>
        {{ 'Invoice Print-'.'DRS'.str_pad($customerLaundry->id, 5, 0, STR_PAD_LEFT)}}
    </title>
    <link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">

    <style>
        th, td {
            padding: 10px;
        }
        .bottom{
            border: 1px solid black;
        }
    </style>
</head>

<body onload="setTimeout(()=>{ window.print() } ,1000)"  onafterprint="setTimeout(()=>{ window.close() } ,5000)">
{{--{{$booking->id}}--}}

<div style="width: 450px; height:750px;border: 2px solid #3c8dbc; margin: 10px; font-size: 10px; display: inline-block; padding-left: 25px;" >
    <table class="table">
        <tr>
            <td colspan="3" style="text-align:center">
                <b style="font-size:20px">INVOICE #{{'DRS'.str_pad($customerLaundry->id, 5, 0, STR_PAD_LEFT)}}</b>
                <br/>
                <span style="display:block;font-size: 20px;font-weight: bold">{{setting('site.title')}}</span>
                <span style="display:block">{!! setting('site.office_address') !!}</span>
                <span style="display:block">{{setting('site.office_phone')}}</span>


            </td>

        </tr>
        <tr>
            <td >
                Customer Name
            </td>
            <td colspan="2">
                {{$customerLaundry->customer->name}}
            </td>
        </tr>
        <tr>
            <td >
                Date Issued:
            </td>
            <td colspan="2">
                {{date('F j, Y',strtotime($customerLaundry->date_received))}}
            </td>
        </tr>
        <tr>
            <td width="50%">
                Item
            </td>
            <td width="20%">
                Quantity
            </td>
            <td width="30%">
                Sub Total
            </td>
        </tr >
        @foreach($customerLaundry->customer_laundry_lines as $ll)

                <tr>
                    <td>{{$ll->item->name}}</td>
                    <td>{{$ll->quantity}}</td>
                    <td>&#8358;{{$ll->total_after_discount}}</td>
                </tr>

        @endforeach

        <tr >
            <td colspan="2" class="bottom">
                Subtotal:
            </td>
            <td  class="bottom">
                &#8358;{{$customerLaundry->total_amount}}
            </td>
        </tr>
        <tr>
            <td colspan="2" class="bottom">
                Discount:
            </td>
            <td  class="bottom">
                &#8358;{{$customerLaundry->discount_applied_amount}}
            </td>
        </tr>
        <tr>
            <td colspan="2" class="bottom">
                After Discount:
            </td>
            <td  class="bottom">
                &#8358;{{$customerLaundry->total_after_discount}}
            </td>
        </tr>
        <tr>
            <td colspan="2" class="bottom">
                Tax Rate:
            </td>
            <td  class="bottom">
                {{$customerLaundry->current_tax_rate}}%
            </td>
        </tr>
        <tr>
            <td colspan="2" class="bottom">
                Tax:
            </td>
            <td class="bottom">
                &#8358;{{$customerLaundry->tax}}
            </td>
        </tr>
        <tr>
            <td colspan="2" class="bottom">
                Total:
            </td>
            <td class="bottom">
                &#8358;{{$customerLaundry->after_tax}}
            </td>
        </tr>
        <tr>
            <td colspan="3" >
                Note:

               {{$customerLaundry->note}}
            </td>
        </tr>

    </table>

</div>



</body>
</html>
