@extends('layouts.default.default')
@section('dueSheet')

<div id="page-title">
    <h3>Due Sheet</h3>
    <div id="breadcrumb-right" class="no-print">
       <div class="float-right">
            <a href="#" id="print" class="btn medium primary-bg" title="">
            <span class="button-content"><i class="glyph-icon icon-list"></i> Print Due Sheet</span>
            </a>
        </div>
    </div>
</div><!-- #page-title -->

<div id="page-content">

    <div class="row">
       <div class="example-box">
          <div class="example-code">
             <!--All Filtering(except sales) started from here -->

            <div class="no-print">
                <h4 class="pad10L pad5B font-bold">Filtering Options:</h4>

                <form action="{{URL::to('dueSheetFiltering')}}" method="post">
                    <div class="form-bordered col-md-12">
                        <div class="form-row">
                           <div class="form-label col-md-1">
                                <label for="">
                                    Shop:
                                </label>
                            </div>
                            <div class="form-input col-md-2">
                                <select id="name" name="shop_name">
                                    <option value="-1">All</option>

                                    @foreach($shops as $shop)
                                    <option value="{{$shop->house_id}}"

                                      <?php
                                      if($selected['shop']==$shop->house_id)
                                        echo " selected";
                                    ?>
                                    >
                                    {{$shop->house_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-label col-md-1">
                                <label for="">
                                    Client:
                                </label>
                            </div>
                            <div class="form-input col-md-3">
                                <select id="name" name="client_name" class="chosen-select">
                                    <option value="-1">All</option>
                                    @foreach($clients as $client)
                                    <option value="{{$client->client_id}}">
                                        {{$client->client_name}}
                                        <?php echo "(".$client->mobile_number.")"?>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-label col-md-2">
                                <label for="">
                                    Product Code:
                                </label>
                            </div>
                            <div class="form-input col-md-3">
                                <select id="name" name="product" class="chosen-select">
                                    <option value="-1">All</option>
                                    @foreach($products as $product)
                                    <option value="{{$product}}"
                                    <?php
                                    if($selected['product']==$product)
                                        echo " selected";
                                    ?>
                                    >
                                    {{$product}}
                                    </option>
                                @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="form-row">

                            <div class="form-label col-md-1">
                                <label for="from">
                                    From:
                                </label>
                            </div>
                            <div class="form-input col-md-2">
                                <input type="text"  class="fromDate datepicker" name="from" value="{{$selected['from']}}">
                            </div>

                            <div class="form-label col-md-1">
                                <label for="to">
                                    To:
                                </label>
                            </div>
                            <div class="form-input col-md-2">
                                <input type="text"  class="toDate datepicker" name="to" value="{{$selected['to']}}">
                            </div>


                            <div class="form-input col-md-2 col-md-offset-4">
                                <input type="submit"  class="font-white btn btn-medium primary-bg" name="submit">
                            </div>

                        </div>
                    </div> <br/>
                </form>
            </div>
            <!--Filtering Ended here -->

<table class="table table-condensed" id="tableToPrint">
    <thead>
       <tr>
          <th>S/L</th>
          <th>Bill No.</th>
          <th>Date</th>
          <th>Client Name</th>
          <th>Products</th>
          <th>Quantity(sft)</th>
          <th>Sale amount(TK)</th>
          <th>Cash payment(TK)</th>
          <th>Less(TK)/<br>Relase(TK)</th>
          <th>Carrying</th>
          <th>Due</th>
      </tr>
  </thead>
  <tbody>

   @if (!is_null($data))
   <?php $i = 1; ?>
   @foreach($data as $info)
       <tr class="singlerows">
          <td>{{$i++}}</td>

          <td>{{$info['bill_id']}}</td>
          <input name="bill_id" type="hidden" value="{{$info['bill_id']}}">
          <td>{{ $data=date('d/m/y', strtotime($info['bill_date']))}}</td>

          <td>{{$info['client_name']}}</td>

        @if(Auth::user()->role != 'sales')
          <td>
            <?php $loop = 0; $prev_product = ""; ?>
             @foreach($info['products'] as $product)
             <?php
                  $loop++;
                  $token = strtok($product, "-");
                  if($token == $prev_product)
                    continue;
                  $prev_product = $token;
                  if($loop>1) echo ",";
             ?>
             {{$token}}
             @endforeach
         </td>

         <td class="qty">
            <?php $amountsum = 0; ?>
             @foreach($info['quantity'] as $amount)
               <?php  $amountsum += $amount; ?>
             @endforeach
            {{round($amountsum,2)}}
         </td>
         <td class="nt">{{round($info['net'],2)}}</td>
         <td class="csh">{{round($info['payment']['cash'],2)}}</td>
         <td class="less">{{round($info['total_less'],2)}}</td>
         <td class="carry">{{round($info['carrying_cost'],2)}}</td>

         @if($info['due']>0.00)
         <td><div class="label bg-red duebill">{{round($info['due'],2)}}</div></td>
         @else
         <td><div class="label bg-green duebill">{{0}}</div></td>
         @endif

        @endif
    </tr>
@endforeach
    <tr class="font-bold font-size-14 pad10T">
        <td></td>
        <td>Total</td>
        <td></td>
        <td></td>
        <td></td>
        <td id="todayqty">0</td>
        <td id="todaynet">0</td>
        <td id="todaycash">0</td>
        <td id="todayless">0</td>
        <td id="todaycarrying">0</td>
        <td id="todaydue">0</td>
    </tr>
@endif
</tbody>
</table>
</div>
</div>
<!-- pagination -->

<div class="float-right no-print">
    <div class="pagination-page"></div>
</div>

<!-- pagination ends-->
</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var qtysum = 0;
        var netsum = 0;
        var duesum = 0;
        var cashsum = 0;
        var totsale = 0;
        var totless = 0;
        var carryingsum = 0;
        $('.singlerows').each(function(){
        //quantity sum
        qtysum +=parseFloat($(this).find('.qty').text());

        //net sum
        netsum += parseFloat($(this).find('.nt').text());

        //carrying sum
        carryingsum += parseFloat($(this).find('.carry').text());

        //less sum
        totless += parseFloat($(this).find('.less').text());

        //due sum
        duesum += parseFloat($(this).find('.duebill').text());

        //cash sum
        var cashvar = parseFloat($(this).find('.csh').text());
        cashsum += cashvar;
    });
    $('#todayqty').text(qtysum.toFixed(2));
    $('#todaycash').text(cashsum.toFixed(2));
    $('#todaynet').text(netsum.toFixed(2));
    $('#todayless').text(totless.toFixed(2));
    $('#todaycarrying').text(carryingsum.toFixed(2));
    $('#todaydue').text(duesum.toFixed(2));
    });
</script>

<!-- pagination and print script -->
<script type="text/javascript">

//pagination function

function paginate(itemperpage)
{
    var items = $("#tableToPrint tbody tr");

    var numItems = items.length;

    var perPage = itemperpage;
// only show the first 2 (or "first per_page") items initially
items.slice(perPage).hide();

// now setup your pagination
// you need that .pagination-page div before/after your table
$(".pagination-page").pagination({
    items: numItems,
    itemsOnPage: perPage,
    cssStyle: "light-theme",
    onPageClick: function(pageNumber) { // this is where the magic happens
        // someone changed page, lets hide/show trs appropriately
        var showFrom = perPage * (pageNumber - 1);
        var showTo = showFrom + perPage;

        items.hide() // first hide everything, then show for the new page
        .slice(showFrom, showTo).show();
    }
});

}

function hidepaginate()
{
    var items = $('#tableToPrint tbody tr');
    items.show();
}

//pagination script
jQuery(function($) {
    var perPage = 20;

//pagination function calling
paginate(perPage);

//print page
$('#print').on('click',function(){
    $('#page-content-wrapper').css({'margin-left':'0px','margin-top':'-50px'});
    hidepaginate(); //hide the pagination for printing purpose
    window.print();
    paginate(perPage);  //again show pagination after completing print
    $('#page-content-wrapper').css({'margin-left':'220px','margin-top':'0px'});
});

});
</script>

@stop
