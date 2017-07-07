@extends('layouts.default.default')

@section('clientList')

 <div id="page-title">
    <h3>Clients List</h3>
    <div id="breadcrumb-right" class="no-print">
        <div class="float-right">

            <a href="{{ URL::route('clients.create') }}" class="btn medium bg-orange" title="">
                <span class="button-content"><i class="glyph-icon icon-plus"></i> Add new CLient</span>
            </a>
            
            <a href="#" id="print" class="btn medium primary-bg" title="">
                <span class="button-content"><i class="glyph-icon icon-list"></i> Print Client List</span>
            </a>
          
        </div>
    </div>
</div><!-- #page-title -->

<div id="page-content">

    <div class="row">
        <div class="example-box">
            <div class="example-code">

                <table class="table table-striped" id="tableToPrint">
                    <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Client ID</th>
                            <th>Client Name</th>
                            <th>Mobile No.</th>
                            <th class="no-print">Details</th>
                        </tr>
                    </thead>
                    <tbody>   

                        <?php 
                            $x = 1;
                        ?>
                        
                        @foreach ($clients as $client) 
                            
                            <tr>
                            <td>{{$x}}</td>
                            <td>{{$client->client_id}}</td>
                            <td class="font-bold text-left">{{$client->client_name}}</td>
                            <td>{{$client->mobile_number}}</td>
                            <td class="no-print">
                                    
                                    <li> 
                                        {{ HTML::decode(HTML::linkRoute('clients.show','<i class="glyph-icon icon-edit"></i>Details',['id'=>$client->client_id])) }}

                                    </li>
                                    
                               
                            </td>

                            </tr>
                            <?php $x++; ?>                           
                            
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