@extends('layouts.default.default')
@section('salesReport')

    <div id="page-title">
        <h3>Return Report</h3>
        <div id="breadcrumb-right" class="no-print">
            <div class="float-right">
                <a href="#" id="print" class="btn medium primary-bg" title="">
                    <span class="button-content"><i class="glyph-icon icon-list"></i> Print Return Report</span>
                </a>
            </div>
        </div>
    </div><!-- #page-title -->

    <div id="page-content">

        <div class="row">
            <div class="example-box">
                <div class="example-code">
                    @if(Auth::user()->role != 'sales')
                            <!--All Filtering(except sales) started from here -->

                    <div class="no-print">
                        <h4 class="pad10L pad5B font-bold">Filtering Options:</h4>

                        <form action="{{URL::to('returnFiltering')}}" method="post">
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

                                                <?php if ($selected['shop'] == $shop->house_id)
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
                                                <option value="{{$client->client_id}}"
                                                <?php
                                                        if($selected['client'] == $client->client_id)
                                                            echo "selected";
                                                        ?>
                                                >
                                                    {{$client->client_name}}
                                                    <?php echo "(" . $client->mobile_number . ")"?>
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
                                        <input type="text" class="fromDate datepicker" name="from"
                                               value="{{$selected['from']}}">
                                    </div>

                                    <div class="form-label col-md-1">
                                        <label for="to">
                                            To:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <input type="text" class="toDate datepicker" name="to"
                                               value="{{$selected['to']}}">
                                    </div>

                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Product Code:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <select id="name" name="product" class="chosen-select">
                                            <option value="-1">All</option>
                                            @foreach($products as $product)
                                                <option value="{{$product}}"
                                                <?php
                                                        if ($selected['product'] == $product)
                                                            echo " selected";
                                                        ?>
                                                >
                                                    {{$product}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <input type="submit" class="font-white btn btn-medium primary-bg" name="submit">
                                    </div>

                                </div>
                            </div>
                            <br/>
                        </form>
                    </div>
                    <!--Filtering Ended here -->
                    @endif

                    <table class="table table-condensed" id="tableToPrint">
                        <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Bill No.</th>
                            <th>Date</th>
                            <th>Client Name</th>
                            <th>Products</th>
                            <th>Quantity(pieces)</th>
                            <th>Quantity(sft)</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if (!is_null($data))
                            <?php $i = 1; ?>
                            @foreach($data as $info)
                                <form onsubmit="return confirm('Do you want to delete this bill?');"
                                      action="{{URL::to('chalanDelete')}}" method="post">
                                    <tr class="singlerows">
                                        <td>{{$i++}}</td>
                                        <td>{{$info['bill_id']}}</td>
                                        <input name="bill_id" type="hidden" value="{{$info['bill_id']}}">
                                        <td>{{ $data=date('d/m/y', strtotime($info['bill_date']))}}</td>
                                        <td>{{$info['client_name']}}</td>
                                        <td>{{$info['product_code']}}</td>
                                        <td class="qtypc">{{$info['quantity']}}</td>
                                        <?php
                                        $size = DB::table('lc_product')->where('product_code', $info['product_code'])->pluck('unit_product_size');
                                        $size = explode('X', $size);
                                        $size = ($size[0] * $size[1]) / 144;
                                        ?>
                                        <td class="qtysft">{{$info['quantity']*$size}}</td>
                                    </tr>
                                </form>
                            @endforeach
                            @if(Auth::user()->role!='sales')
                                <tr class="font-bold font-size-14 pad10T">
                                    <td></td>
                                    <td>Total</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td id="todaypc">0</td>
                                    <td id="todaysft">0</td>
                                </tr>
                            @endif
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- pagination -->

            <div class="float-right no-print">
                <div class="pagination-page"></div>
            </div>


        </div>
    </div>

        <script type="text/javascript">
            $(document).ready(function () {
                var sftsum = 0;
                var pcsum = 0;

                $('.singlerows').each(function () {
                    //quantity sum
                    sftsum +=  parseFloat($(this).find('.qtysft').text());
                    pcsum += parseFloat($(this).find('.qtypc').text());

                });

                $('#todaypc').text(pcsum.toFixed(2));
                $('#todaysft').text(sftsum.toFixed(2));
              });

        </script>

      <!-- pagination and print script -->
        <script type="text/javascript">
            //pagination function
            function paginate(itemperpage) {
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
                    onPageClick: function (pageNumber) { // this is where the magic happens
                        // someone changed page, lets hide/show trs appropriately
                        var showFrom = perPage * (pageNumber - 1);
                        var showTo = showFrom + perPage;

                        items.hide() // first hide everything, then show for the new page
                                .slice(showFrom, showTo).show();
                    }
                });

            }

            function hidepaginate() {
                var items = $('#tableToPrint tbody tr');
                items.show();
            }

            //pagination script
            jQuery(function ($) {
                var perPage = 20;

//pagination function calling
                paginate(perPage);

//print page
                $('#print').on('click', function () {
                    $('#page-content-wrapper').css({'margin-left': '0px', 'margin-top': '-50px'});
                    hidepaginate(); //hide the pagination for printing purpose
                    window.print();
                    paginate(perPage);  //again show pagination after completing print
                    $('#page-content-wrapper').css({'margin-left': '220px', 'margin-top': '0px'});
                });

            });
        </script>

@stop
