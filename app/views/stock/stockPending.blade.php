@extends('layouts.default.default')
@section('stockPending')

    <div id="page-title">
        <h3>Pending Bills of Stock</h3>
    </div><!-- #page-title -->

    <div id="page-content">

        <div class="row">
            <div class="example-box">
                <div class="example-code">

                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Chalan Bill No.</th>
                            <th>Date</th>
                            <th>Client Name</th>
                            <th>Products</th>
                            <th>Quantity<br/>(Pieces)</th>
                            <th>Pending</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $chalans = DB::table('chalan_parent')->where('clear',0)->get();
                        $i = 1
                        ?>

                        @foreach($chalans as $chalan)

                            <form action="{{URL::to('stockClear')}}" method="post">
                                <tr>
                                    <td>{{$i++}}</td>

                                    <td>{{$chalan->id}}</td>

                                    <input type="hidden" name="clear_id" value="{{$chalan->id}}"/>

                                    <td>{{ $data=date('d/m/y', strtotime($chalan->created_at))}}</td>

                                    <td>{{
                                        DB::table('client')
                                        ->where('client_id',DB::table('bill_info')
                                            ->where('bill_id',$chalan->parent_bill)
                                            ->pluck('client_id')
                                        )
                                        ->pluck('client_name')}}
                                    </td>

                                    <td>
                                        <?php $products = DB::table('chalan_product')->where('chalan_id', $chalan->id)->lists('product_id');?>
                                        @foreach($products as $product)
                                            {{$product}}<br/>
                                        @endforeach
                                    </td>

                                    <td>
                                        <?php $products = DB::table('chalan_product')->where('chalan_id', $chalan->id)->lists('total_piece');?>
                                        @foreach($products as $product)
                                            {{$product}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        <input type="submit" value="Clear" class="btn primary-bg"/>
                                    </td>

                                </tr>

                            </form>
                        @endforeach

                        </tbody>
                    </table>


                </div>


            </div>
            <!-- pagination -->

            <div class="float-right">
                <div class="pagination-page"></div>
            </div>

            <!-- pagination ends-->
        </div>
    </div>

    <!-- pagination  script -->
    <script type="text/javascript">
        //pagination script

        // mind the slight change below, personal idea of best practices
        jQuery(function ($) {
            // consider adding an id to your table,
            // just incase a second table ever enters the picture..?
            var items = $("table tbody tr");

            var numItems = items.length;
            var perPage = 20;

            // only show the first 2 (or "first per_page") items initially
            items.slice(perPage).hide();

            // now setup your pagination
            // you need that .pagination-page div before/after your table
            $(".pagination-page").pagination({
                items: numItems,
                itemsOnPage: perPage,
                cssStyle: "light-theme",
                onPageClick: function (pageNumber) { // this is where the magic happens
                    // someone changed page, lets hide/show trs appropriately
                    var showFrom = perPage * (pageNumber - 1);
                    var showTo = showFrom + perPage;

                    items.hide() // first hide everything, then show for the new page
                            .slice(showFrom, showTo).show();
                }
            });
        });
    </script>
@stop