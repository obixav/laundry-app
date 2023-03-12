<html>
<head><title>
        {{ 'Receipt Print-'.'DRS'.str_pad($payment->id, 5, 0, STR_PAD_LEFT)}}
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
            <td colspan="2" style="text-align:center">
                <b style="font-size:20px">RECEIPT #{{'DRS'.str_pad($payment->id, 5, 0, STR_PAD_LEFT)}}</b>
                <br/>
                Invoice #{{'DRS'.str_pad($customerLaundry->id, 5, 0, STR_PAD_LEFT)}}
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
            <td >
                {{$customerLaundry->customer->name}}
            </td>
        </tr>
        <tr>
            <td >
                Date Issued:
            </td>
            <td >
                {{date('F j, Y',strtotime($customerLaundry->date_received))}}
            </td>
        </tr>
        <


        <tr >
            <td  class="bottom">
                Date of Payment:
            </td>
            <td  class="bottom">
                {{date('F j, Y',strtotime($payment->date))}}
            </td>
        </tr>
        <tr>
            <td  class="bottom">
                Previous Balance:
            </td>
            <td  class="bottom">
                &#8358;{{$payment->previous_debt}}
            </td>
        </tr>
        <tr>
            <td  class="bottom">
                Amount Paid:
            </td>
            <td  class="bottom">
                &#8358;{{$payment->amount_paid}}
            </td>
        </tr>
        <tr>
            <td  class="bottom">
                Balance After Payment:
            </td>
            <td  class="bottom">
                &#8358;{{$payment->debt_after_payment}}
            </td>
        </tr>
        <tr>
            <td  class="bottom">
                Payment Mode:
            </td>
            <td  class="bottom">
                {{$payment->payment_type}}
            </td>
        </tr>

        <tr>
            <td colspan="2" >
                Received By:

               {{$payment->author->name}}
            </td>
        </tr>
        <tr>
            <td colspan="2" >
                Note:

                {{$payment->description}}
            </td>
        </tr>

    </table>

</div>



</body>
</html>
