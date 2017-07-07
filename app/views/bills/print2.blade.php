@include('layouts.default.metaHead')

<div id="scoped-content">

{{HTML::style('printBillAssets/invoice.css')}}
<div class="no-print mrg20T" style="margin-left: 45%;">
<a href="#" id="print" class="btn large bg-red" title="">
    <span class="button-content"><i class="glyph-icon icon-list"></i> Print Bill</span>
</a>
</div>
<div id="page"  class="pad15T">
	<div id="bill-head" class="text-center">
    <h2 class="text-center">Due Payment Memo</h2>
        @if($salesman_house==2)
        <h2 class="font-bold font-blue">সামির টাইলস এন্ড স্যানিটারী</h2>
        <h2 class="mrg0T font-green">Samir Tiles and Sanitary</h2>
        @elseif($salesman_house==3)
        <h2 class="font-bold font-blue">সিয়াম টাইলস এন্ড স্যানিটারী</h2>
        <h2 class="mrg0T font-green">Seayam Tiles and Sanitary</h2>
        @endif

        <hr>
        <span class="font-bold">All Kinds of Tiles, Marble, Granite, Sanitary Items Importer, Supplier &amp; Seller</span><br/>
        @if($salesman_house==3)
        <div class="mrg5T">7,Link road, Banglamotor, Dhaka-1205 <br/>
        Tel: 9613906, Cell: 01711109876, 01969604200</div>
        @elseif($salesman_house==2)
        <div class="mrg5T">340/1(old),78,Bir uttam C.R. Datta road, Hatirpool, Dhaka-1205 <br/>
        Tel: 9613906, Cell: 01711109876, 01969604200</div>
        @endif
    </div>

	@if($bill_id)
        <div id="bill-info"class="row mrg20T">
        <div class="col-xs-12 form-bordered">
            <div class="form-row pad10T">
                <div class="col-xs-9 text-left">
                    <span class="font-bold">Bill No:</span>
                    {{$bill_id}}
                </div>

                <div class="col-xs-3 text-left">
                    <span class="font-bold">{{$bill_for}}</span>
                </div>
            </div>
            <div class="form-row pad10T">
                <div class="col-xs-9 text-left">
                    <span class="font-bold">Client Name:</span>
                        {{$client->client_name}}
                </div>
                <div class="col-xs-3 text-left">
                    <span class="font-bold">Date:</span>
                       {{date("d/m/Y (h:i)")}}
                </div>
            </div>
            <div class="form-row pad10T pad10B">
                <div class="col-xs-9 text-left">
                    <span class="font-bold">Address:</span>
                        {{$client->address}}
                </div>
                <div class="col-xs-3 text-left">
                    <span class="font-bold">Phone:</span>
                        {{$client->mobile_number}}
                </div>
            </div>
        </div>
    </div>
    <div id="product-info" class="mrg20T form-bordered">
        <table class="table table-condensed text-center">
            <thead>
                <tr>
                    <th class="text-center">Description</th>
                    <th class="text-center">AMOUNT(BDT)</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Due Payment</td>
                <td class="font-bold">{{ $due->cheque + $due->cash + $due->credit_card}}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <hr style="margin-top:-20px;">
    <div id="bill-total" class="row">
        <div class="col-xs-4 form-bordered pad0A mrg25B">
            <div class="form-row">
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label for="">
                        Cash:
                    </label>
                </div>
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label for="">
                        {{$due->cash}}
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label for="">
                        Cheque:
                    </label>
                </div>
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label for="">
                        {{$due->cheque}}
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label for="">
                        Credit Card:
                    </label>
                </div>
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label for="">
                        {{$due->credit_card}}
                    </label>
                </div>
            </div>
        </div>
        <div class="col-xs-3"></div>
        <div class="col-xs-5 form-bordered pad0A mrg25B">
            <div class="form-row">
                <div class="form-label col-xs-5 pad0T pad0B">
                    <label for="">
                        Prev. Due:
                    </label>
                </div>
                <div class="form-label col-xs-7 pad0T pad0B">
                    <label for="">
                        {{$due_now + $due->cheque + $due->cash + $due->credit_card + $due->less}}
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-xs-5 pad0T pad0B">
                    <label for="">
                        Discount:
                    </label>
                </div>
                <div class="form-label col-xs-7 pad0T pad0B">
                    <label for="">
                        {{$due->less}}
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-xs-5 pad0T pad0B">
                    <label for="">
                        Paid
                    </label>
                </div>
                <div class="form-label col-xs-7 pad0T pad0B">
                    <label for="">
                        {{$due->cheque + $due->cash + $due->credit_card}}
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-xs-5 pad0T pad0B">
                    <label for="">
                        Due Now:
                    </label>
                </div>
                <div class="form-label col-xs-7 pad0T pad0B">
                    <label for="">
                        {{$due_now ? $due_now : 0 }}
                    </label>
                </div>
            </div>
            </div>
        <div class="col-xs-4 mrg20T">
            <br>
            <span>-----------------------</span><br>
            <span>Client's Signature</span>
        </div>
        <div class="col-xs-5"></div>
        <div class="col-xs-3 mrg20T">

            {{$salesman}}
            <br>
            <span>----------------------------</span><br>
            <span>Salesman's Signature</span>
        </div>
    </div>
    @endif


</div>
<div class="page-shadow"></div>
</div>
<div class="row">
    <div class="text-center">
        <strong>Developed &amp; maintained by:</strong> <span style="color: red;"><i>Megaminds</i></span> <span style="color: rgb(75, 75, 128);">(Web &amp; IT solutions)</span><br>
        <strong>Contact:</strong> 01921099556, 01710340450
    </div>
</div>
<script>
$(document).ready(function(){
    $('#print').on('click',function(){
        window.print();
    })
});

</script>