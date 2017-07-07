@extends('layouts.default.default')

@section('productList')

 <div id="page-title">
    <h3>All Product List</h3>
    <div id="breadcrumb-right" class="no-print">
        <div class="float-right">
            @if(Auth::user()->role == 'stock')
            <a href="{{URL::route('products.create')}}" class="btn medium bg-orange" title="">
                <span class="button-content"><i class="glyph-icon icon-plus"></i> Add New Product</span>
            </a>
            @endif

            @if(Auth::user()->role != 'sales')
            <a href="#" id="print" class="btn medium primary-bg" title="">
                <span class="button-content"><i class="glyph-icon icon-list"></i> Print Product List</span>
            </a>
            @endif

        </div>
    </div>
</div><!-- #page-title -->

<div id="page-content">

    <div class="row">
        <div class="example-box">
            <div class="example-code">
                <!-- Filtering started from here -->
                <div class="no-print">
                    <h4 class="pad10L pad5B font-bold">Filtering Options:</h4>

                    <form action="{{URL::to('productFiltering')}}" method="post">
                    <div class="form-bordered col-md-12">
                        <div class="form-row">
                            <div class="form-label col-md-2">
                                <label for="">
                                    Unit Size:
                                </label>
                            </div>
                            <div class="form-input col-md-3">
                                <select id="name" name="unit_size">
                                    <option value="-1">All</option>
                                    @foreach ($units_size as $unit)
                                        <option value="{{$unit->unit_product_size}}"
                                            <?php
                                                if($selected == $unit->unit_product_size)
                                                    echo " selected";
                                            ?>

                                        >{{$unit->unit_product_size}}</option>
                                    @endforeach

                                </select>
                            </div>
                             @if(Auth::user()->role != 'sales')
                            <div class="form-label col-md-2">
                                <label for="">
                                    Single/Group:
                                </label>
                            </div>
                            <div class="form-input col-md-3">
                                <select id="name" name="group_by">
                                    <option value="0">All Products</option>
                                    <option value="1"
                                    <?php
                                        if($grp){
                                            echo " selected";
                                        }
                                    ?>
                                    >Group By</option>
                                </select>
                            </div>
                            @endif

                            <div class="form-input col-md-2">
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
                            <th>Product Code</th>

                            @if(!$grp)
                                <th>Unit Size</th>
                                @if(Auth::user()->role != 'sales')
                                <th>LC-number</th>
                                @endif
                            @endif
                            @if(Auth::user()->role != 'sales')
                            <th>Total Stock<br/>(Pieces)</th>
                            @if(!$grp)
                            <th>Total Stock<br/>(SFT)</th>
                            @endif
                            <th>Wastage<br/>(Before Stock)</th>
                            <th>Wastage<br/>(After Stock)</th>
                            <th>Total Sold<br/>(Pieces)</th>
                            @endif
                            <th>Current Stock<br/>(Pieces)</th>
                            @if(!$grp)
                            <th>Current Stock<br/>(SFT)</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                    @if(isset($data))
                        <?php $x=1 ?>
                        @foreach($data as $value)
                        <tr class="singlerows">
                            <td>{{ $x++ }}</td>
                            <td class="font-bold text-left">{{$value['product_code']}}</td>

                            @if(!$grp)
                                    <td>{{$value['unit_size'][0]}}</td>
                                    @if(Auth::user()->role != 'sales')
                                    <td>
                                    @foreach($value['lc_number'] as $lc)
                                        {{$lc}}
                                        {{"<br/>"}}
                                    @endforeach
                                    </td>
                                    @endif
                            @endif

                            @if(!$grp)
                            <?php
                                $size = explode("X", $value['unit_size'][0]);
                                $sft_cnt = round(($size[0]*$size[1])/144.0, 2);
                            ?>
                            @endif

                            @if(Auth::user()->role != 'sales')
                            <td class="totalpiece">{{$value['total_in']}}</td>
                            @if(!$grp)
                            <td class="totalsft">{{$value['total_in'] * $sft_cnt}}</td>
                            @endif
                            <td class="wbs">{{$value['w_b_s']}}</td>
                            <td class="was">{{$value['w_a_s']}}</td>
                            <td class="totalsold font-bold text-left">{{$value['ttl_sld']}}</td>
                            @endif
                            <td class="curpiece font-bold text-left">{{$value['current_stock']}}</td>
                            @if(!$grp)
                            <td class="cursft font-bold text-left">{{$value['current_stock'] * $sft_cnt}}</td>
                            @endif
                        </tr>
                        @endforeach

                        <tr class="font-bold font-size-14 pad10T">
                            <td></td>
                            <td>Total</td>
                            @if(!$grp)
                              @if(Auth::user()->role != 'sales')
                              <td></td>
                              @endif
                            <td></td>
                            @endif
                            @if(Auth::user()->role != 'sales')
                            <td id="totalpiece">0</td>
                            @if(!$grp)
                            <td id="totalsft">0</td>
                            @endif
                            <td id="totalwaste_b_s">0</td>
                            <td id="totalwaste_a_s">0</td>
                            <td id="totalsale">0</td>
                            @endif
                            <td id="currentpiece">0</td>
                            @if(!$grp)
                            <td id="currentsft">0</td>
                            @endif

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

<!-- total calculation at the end of the table script -->
<script type="text/javascript">
    $(document).ready(function(){
        var totalpiecesum = 0;
        var totalsftsum = 0;
        var waste_b_ssum = 0;
        var waste_a_ssum = 0;
        var totalsoldsum = 0;
        var curpiecesum = 0;
        var cursftsum = 0;
        $('.singlerows').each(function(){

        //total piece sum
        totalpiecesum += parseInt($(this).find('.totalpiece').text());

        //toal sft sum
        totalsftsum += parseFloat($(this).find('.totalsft').text());

        //waste before stock sum
        waste_b_ssum += parseInt($(this).find('.wbs').text());

        //waste after stock sum
        waste_a_ssum += parseInt($(this).find('.was').text());

        //total sold sum
        totalsoldsum += parseInt($(this).find('.totalsold').text());

        //current piece sum
        curpiecesum += parseInt($(this).find('.curpiece').text());

        //current sft sum
        cursftsum += parseFloat($(this).find('.cursft').text());
    });
$('#totalpiece').text(totalpiecesum);
if(!isNaN(totalsftsum))
{
    $('#totalsft').text(totalsftsum);
}
$('#totalwaste_b_s').text(waste_b_ssum);
$('#totalwaste_a_s').text(waste_a_ssum);
$('#totalsale').text(totalsoldsum);
$('#currentpiece').text(curpiecesum);
if(!isNaN(cursftsum))
{
    $('#currentsft').text(cursftsum);
}
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
