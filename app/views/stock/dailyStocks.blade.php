@extends('layouts.default.default')
@section('dailyStock')
 <div id="page-title">
    <h3>Daily Stock Report</h3>
    <div id="breadcrumb-right" class="no-print">
        <div class="float-right">
            <a href="#" id="print" class="btn medium primary-bg" title="">
                <span class="button-content"><i class="glyph-icon icon-list"></i> Print Daily Stock Sheet</span>
            </a>      
        </div>
    </div>
</div><!-- #page-title -->

<div id="page-content">

    <div class="row">
        <div class="example-box">
            <div class="example-code">

                <table class="table table-condensed" id="tableToPrint">
                    <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Product Code</th>
                            <th>Unit Size</th>
                            <th>Pieces available<br/>(Opening)</th>
                            <th>Total Sold Today<br/>(Pieces)</th>
                            <th>Pieces available<br/>(Closing)</th>
                            <th>Total Stock(sft)<br/>(Opening)</th>
                            <th>Total Sold Today<br/>(sft)</th>
                            <th>Total Stock(sft)<br/>(Closing)</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(isset($data))
                    <?php $i=1; ?>
                        @foreach($data as $value)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$value['product_code']}}</td>
                                <td>{{$value['unit_product_size']}}</td>
                                <td>{{$value['opening_stock']}}</td>
                                <td class="font-bold">{{$value['total_sold_today']}}</td>
                                <td>{{$value['pieces_available']}}</td>
                                <td class="font-bold">{{ round($value['total_stock'],2) }}</td>
                                <td class="font-bold">{{$value['total_sold_today_sft']}}</td>
                                <td class="font-bold">{{ round($value['closing'],2) }}</td>
                            </tr>
                        @endforeach
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