@include('layouts.default.metaHead')
<div id="scoped-content">

{{HTML::style('printBillAssets/invoice.css')}}


<div class="no-print mrg20T" style="margin-left: 45%;">
<a href="#" id="print" class="btn large bg-red" title="">
    <span class="button-content"><i class="glyph-icon icon-list"></i> Print Bill</span>
</a>
</div>
<div id="page" class="pad5T">
	<div id="bill-head" class="text-center">
	<h2 class="text-center">Challan</h2>
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
	<div id="bill-info"class="row mrg5T">
		<div class="col-xs-12 form-bordered">
			<div class="form-row pad10T">
				<div class="col-xs-9 text-left">
					<span class="font-bold">Bill No:</span>
                    {{$bill_info->bill_id}}
				</div>
      </div>
      <div class="form-row pad10T">
				<div class="col-xs-9 text-left">
					<span class="font-bold">Client Name:</span>
                        {{$client->client_name}}
				</div>
				<div class="col-xs-3 text-left">
					<span class="font-bold">Date:</span>
                        {{date("d/m/Y (h:i)",strtotime($challan->created_at))}}
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
	<div id="product-info" class="mrg20T form-bordered" style="min-height: 300px;">
		<table class="table table-condensed text-center">
            <thead>
                <tr>
                    <th class="text-center">SL.</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Size</th>
                    <th class="text-center">QTY.(sft)</th>
                    <th class="text-center">QTY.(Box)</th>
                    <th class="text-center">QTY.(Pieces)</th>

                </tr>
            </thead>
            <tbody>
            <?php
            	$i = 1;
            	$xyz=1;
            	$p_size = null;
            ?>
                @foreach($products as $product)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$product->product_id}}</td>
                        	<?php
                        		$xyz = ProductTracker::where('product_code',$product->product_id)->first();
                            $box = $xyz->box;
                        	?>
                        	<?php
                        	  $sizes = Product::where('product_code',$product->product_id)->first();
                        		$p_size = $sizes->unit_product_size;
                        	?>
	                    <td class="font-bold">{{$p_size}} </td>
	                    <td class="font-bold">{{$product->product_quantity}} </td>
                      <td class="font-bold">{{floor($product->total_piece/$box)}}</td>
                      <td class="font-bold">{{fmod($product->total_piece, $box)}}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
	</div>
	<hr style="margin-top:-20px;">
	<div id="bill-total" class="row">

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
        <strong>web:</strong> www.megaminds.co
    </div>
</div>
<script>

$(document).ready(function(){
    $('#print').on('click',function(){
        window.print();
    })
});

</script>
