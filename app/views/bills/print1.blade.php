@include('layouts.default.metaHead')
<div id="scoped-content">

{{HTML::style('printBillAssets/invoice.css')}}


<div class="no-print mrg20T" style="margin-left: 45%;">
<a href="#" id="print" class="btn large bg-red" title="">
    <span class="button-content"><i class="glyph-icon icon-list"></i> Print Bill</span>
</a>
</div>
<div id="page" class="pad15T">
	<div id="bill-head" class="text-center">
        <h2 class="text-center">Cash Memo</h2>
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
	<div id="bill-info"class="row mrg20T">
		<div class="col-xs-12 form-bordered">
			<div class="form-row pad10T">
				<div class="col-xs-3 text-left">
					<span class="font-bold">Bill No:</span>
                    {{$bill_info->bill_id}}
				</div>
				<div class="col-xs-6 text-center">
					<span class="font-bold">{{$bill_for}}</span>
				</div>
        <div class="col-xs-3 text-left">
          <span class="font-bold">Date:</span>
              {{date("d/m/Y (h:i)",strtotime($bill_info->bill_date))}}
				</div>
        </div>
        <div class="form-row pad10T">
				<div class="col-xs-9 text-left">
					<span class="font-bold">Client Name:</span>
                        {{$client->client_name}}
				</div>
				<div class="col-xs-3 text-left">
					<span class="font-bold">Ref. Date:</span>
                        {{date("d/m/Y",strtotime($bill_info->ref_bill_date))}}
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
                    <th class="text-center">SL.</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Size</th>
                    <th class="text-center">QTY.(sft)</th>
                    <th class="text-center">RATE(BDT)</th>
                    <th class="text-center">AMOUNT(BDT)</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1; ?>
                @foreach($products as $product)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$product->product_code}}</td>
                        <td>{{$product->size}}</td>
                        <td class="font-bold sftqty">{{$product->product_quantity}}</td>
                        <td>
                            {{
                                $unit = ($product->adjust_unit_price>0) ? $product->adjust_unit_price:$product->unit_sale_price
                            }}
                        </td>
                        <td class="font-bold">{{$unit*$product->product_quantity}}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
	</div>
	<hr style="margin-top:-20px;">
	<div id="bill-total" class="row">
        <div class="col-xs-4 form-bordered pad0A mrg25B">
            <div class="form-row">
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label for="">
                        Total Qty.(sft):
                    </label>
                </div>
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label id="totalqty">

                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label for="">
                        Cash:
                    </label>
                </div>
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label for="">
                        {{$bill_info->cash}}
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
                        {{$bill_info->cheque}}
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
                        {{$bill_info->credit_card}}
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label for="">
                        Carrying Cost:
                    </label>
                </div>
                <div class="form-label col-xs-6 pad0T pad0B">
                    <label for="">
                        {{$bill_info->carrying_cost}}
                    </label>
                </div>
            </div>
        </div>
        <div class="col-xs-4"></div>

		<div class="col-xs-4 form-bordered pad0A mrg25B">
			<div class="form-row">
				<div class="form-label col-xs-4 pad0T pad0B">
                    <label for="">
                        Gross:
                    </label>
                </div>
                <div class="form-label col-xs-8 pad0T pad0B">
                    <label for="">

                        {{
                            $gross = $bill_info->adjust_gross ? $bill_info->adjust_gross : $bill_info->gross
                        }}
                    </label>
                </div>
            </div>
            <div class="form-row">
				<div class="form-label col-xs-4 pad0T pad0B">
                    <label for="">
                        Discount:
                    </label>
                </div>
                <div class="form-label col-xs-8 pad0T pad0B">
                    <label for="">

                        {{
                            $less = $bill_info->adjust_discount ? $bill_info->adjust_discount : $bill_info->less
                        }}
                    </label>
                </div>
            </div>
            <div class="form-row">
				<div class="form-label col-xs-4 pad0T pad0B">
                    <label for="">
                        Net
                    </label>
                </div>
                <div class="form-label col-xs-8 pad0T pad0B">
                    <label for="">
                        {{$net = $gross - $less}}
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-xs-4 pad0T pad0B">
                    <label for="">
                        Paid
                    </label>
                </div>
                <div class="form-label col-xs-8 pad0T pad0B">
                    <label for="">
                        {{$paid = $bill_info->cash + $bill_info->cheque + $bill_info->credit_card}}
                    </label>
                </div>
            </div>
            <div class="form-row">
				<div class="form-label col-xs-4 pad0T pad0B">
                    <label for="">
                        Due:
                    </label>
                </div>
                <div class="form-label col-xs-8 pad0T pad0B">
                    <label for="">
                        {{$due=$net+$bill_info->carrying_cost - $paid}}
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

    var sftcount = 0;
    $('.sftqty').each(function(){
        var cnt = $(this).text();
        sftcount+=parseFloat(cnt);
    });
    $('#totalqty').text(sftcount);
});

</script>
