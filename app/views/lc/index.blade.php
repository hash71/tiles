@extends('layouts.default.default')
@section('lcList')

 <div id="page-title">
    <h3>LC Information</h3>
    <div id="breadcrumb-right" class="no-print">
        <div class="float-right">
            <a href="#" id="print" class="btn medium primary-bg" title="">
                <span class="button-content"><i class="glyph-icon icon-list"></i> Print LC Information</span>
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
                            <th>LC-number</th>
                            <th>LC Date</th>
                            
                            <th>Products List</th>
                            <th>Quantity<br>(Pieces)</th>       
                        </tr>
                    </thead>
                    <tbody>

                    <?php $y = 0; ?>

                    @foreach($lcs as $lc)
                         <tr>
                            <td>{{$y+1}}</td>
                            <td class="font-bold text-left">{{$lc->lc_number}}</td>
                            <td>{{$lc->lc_date}}</td>
                            
                            <td>
                                @foreach ($product_list[$y] as $plist)
                                    
                                    <!-- $plist->product_code -->
                                    {{$plist->product_code}}
                                    {{"<br/>"}}
                                @endforeach
                            </td>
                            <td>
                                @foreach ($product_list[$y++] as $plist)
                                    {{$plist->quantity}}
                                    {{"<br/>"}}
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
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