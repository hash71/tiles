@extends('layouts.default.default')
@section('incomeSheet') 
 <div id="page-title">
    <h3>Income Sheet</h3>
    <div id="breadcrumb-right" class="no-print">
        <div class="float-right">
            <a href="#" id="print" class="btn medium primary-bg" title="">
                <span class="button-content"><i class="glyph-icon icon-list"></i> Print Income Sheet</span>
            </a>
          
        </div>
    </div>
</div><!-- #page-title -->

<div id="page-content">

    <!-- Filtering started from here -->
    <div class="no-print">
    <h4 class="pad10L pad5B font-bold">Filtering Options:</h4>


    <form action="{{URL::to('calculations/incomeSheetFiltering')}}" method="post">
    <div class="form-bordered col-md-12">
        <div class="form-row">
            
                                  
            
            <div class="form-label col-md-2">
                <label for="">
                    Category:
                </label>
            </div>
            <div class="form-input col-md-4">
                <select id="name" name="category" class="chosen-select">
                    <option value="-1">All</option>

                    @foreach($categories as $category)

                        <option value="{{$category->name}}"

                            <?php 
                                if($selected['category']==$category->name)
                                    echo " selected";
                            ?>

                        >{{$category->name}}</option>                        
                    @endforeach
                </select>
            </div>
            <div id="bankname">
                <div class="form-label col-md-2">
                    <label for="gross">
                        Bank Name:
                    </label>
                </div>
                <div class="form-input col-md-4">
                    <select id="name" name="bank" class="chosen-select">
                        <option value="-100">-------Select Option-------</option>
                        @foreach($bank as $b)
                            <option value="{{$b}}">{{$b}}</option>        
                        @endforeach
                        
                    </select>
                </div>
            </div>

        </div>
        <div class="form-row">
            <div class="form-label col-md-2">
                    <label for="">
                        Shop/Office:
                    </label>
                </div>
                <div class="form-input col-md-2">
                    <select id="name" name="house">
                        <option value="-1">All</option>                         
                        @foreach($houses as $house)
                            <option value="{{$house->house_id}}"
                                <?php 
                                    if($selected['house']==$house->house_id)
                                        echo " selected";
                                ?>

                            >{{$house->house_name}}</option>                         
                        @endforeach
                        
                    </select>
                </div>
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

            <div class="form-input col-md-2">
                <input type="submit"  class="font-white btn btn-medium primary-bg" name="submit">
            </div>

        </div>
    </div> <br/>
    </form>
    </div>
    <!--Filtering Ended here -->

    <div class="row">
        <div class="example-box">
            <div class="example-code">

                <table class="table table-striped" id="tableToPrint">
                    <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Date</th>  
                            <th>Income at(Shop)</th>                          
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $x = 1;?>
                        @foreach($incomes as $income)
                        <tr>
                            <td>{{$x++}}</td>                            
                            <td>{{date('d/m/y', strtotime($income->date))}}</td>
                            <td>
                                @foreach($houses as $house)
                                    @if($house->house_id == $income->house_id)
                                        {{ $house->house_name }}
                                    @endif
                                @endforeach
                            </td>
                            <td class="font-bold text-left">{{$income->category}}</td>
                            <td class="font-bold text-left">{{$income->description}}</td>
                            <td class="font-bold text-left income">{{$income->amount}}</td>
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
        <div class="totaltable">
            <div class="col-xs-4"></div>
            <div class="col-xs-4">
                <div class="text-center font-bold mrg10A">
                    Total Income
                </div>
                <table class="table table-condensed">
                    <thead></thead>
                    <tbody>
                        <tr>
                            <td class="font-bold">Total Income</td>
                            <td id="totalin" class="font-bold">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- pagination and print script -->
<script type="text/javascript">

$(function(){
    var totalcnt = 0;
    $('.income').each(function(){
        var cnt = parseFloat($(this).text());
        if(!isNaN(cnt))
        {
            totalcnt+=cnt;
        }
    });
    $('#totalin').text(totalcnt);
});

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

    //bank option
    $('#name').change(
        function(){
            var selected_val = $('#name option:selected').val();

            var index = selected_val.search(/bank/i); //case insensitive regex word search
            
            if(index<0 && selected_val!=-1) //-1 is for 'All' selected
                $('#bankname').hide();
            else
                $('#bankname').show();
        }
    );

});
</script>

@stop