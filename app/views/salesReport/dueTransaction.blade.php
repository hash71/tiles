@extends('layouts.default.default')
@section('dueTransaction')

    <div id="page-title">
        <h3>Due Collection Sheet</h3>
        <div id="breadcrumb-right" class="no-print">
            <div class="float-right">
                <a href="#" id="print" class="btn medium primary-bg" title="">
                    <span class="button-content"><i class="glyph-icon icon-list"></i> Print Due Collection Sheet</span>
                </a>
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

                        <form action="{{URL::to('dueTransactionFiltering')}}" method="post">
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
                                            @foreach ($shops as $shop)
                                                <option value="{{$shop->house_id}}"
                                                <?php
                                                        if ($selected['shop_id'] == $shop->house_id) {
                                                            echo " selected";
                                                        }
                                                        ?>

                                                >{{$shop->house_name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
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
                                    <div class="form-input col-md-2">
                                        <input type="submit" class="font-white btn btn-medium primary-bg" name="submit">
                                    </div>

                                </div>
                            </div>
                            <br/>
                        </form>
                    </div>
                    <!--Filtering Ended here -->

                    <table class="table table-condensed" id="tableToPrint">
                        <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Bill No.</th>
                            <th>Due Pay Date</th>
                            <th>Client Name</th>
                            <th>Total Due</th>
                            <th>Cash</th>
                            <th>Cheque</th>
                            <th>Credit</th>
                            <th>Less</th>
                            <th>Current Due</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php $x = 1;?>

                        @foreach($transactions as $transaction)
                            <tr class="singlerows">
                                <td>{{$x++}}</td>
                                <td>{{$transaction->bill_id}}</td>
                                <td>{{date('d-m-Y',strtotime($transaction->due_pay_date))}}</td>
                                {{--<td>--}}
                                {{--@if(isset($clients))--}}
                                {{--@foreach ($clients as $client)--}}
                                {{--@if($client->bill_id == $transaction->bill_id)--}}
                                {{--@foreach($client_name as $c)--}}
                                {{--@if($c->client_id == $client->client_id)--}}
                                {{--{{$c->client_name}}--}}
                                {{--@endif--}}
                                {{--@endforeach--}}
                                {{--@endif--}}
                                {{--@endforeach--}}
                                {{--@endif--}}
                                {{--</td>--}}
                                <td>
                                    {{Client::where('client_id',Bill::where('bill_id',$transaction->bill_id)->pluck('client_id'))->pluck('client_name')}}
                                </td>
                                <td class="prevdue">
                                    {{--@foreach($due_amounts as $due_amount)--}}
                                    {{--@if($transaction->bill_id == $due_amount->bill_id)--}}
                                    {{--{{$transaction->prev_due}}--}}
                                    {{--@endif--}}
                                    {{--@endforeach--}}
                                    {{
                                    $transaction->cash+$transaction->cheque+$transaction->credit_card+$transaction->less+DB::table('due')->where('bill_id',$transaction->bill_id)->pluck('due_amount')

                                    }}
                                </td>


                                <td class="cash">{{$transaction->cash}}</td>
                                <td class="chq">{{$transaction->cheque}}</td>
                                <td class="credit">{{$transaction->credit_card}}</td>
                                <td class="less">{{$transaction->less}}</td>
                                <td class="curdue">
                                    {{--@foreach($due_amounts as $due_amount)--}}
                                    {{--@if($transaction->bill_id == $due_amount->bill_id)--}}
                                    {{--{{ $transaction->prev_due - ($transaction->cash + $transaction->cheque+$transaction->credit_card + $transaction->less)}}--}}
                                    {{--@endif--}}
                                    {{--@endforeach--}}
                                    {{DB::table('due')->where('bill_id',$transaction->bill_id)->pluck('due_amount')}}
                                </td>

                            </tr>
                        @endforeach
                        @if($x>1)
                            <tr class="font-bold font-size-16 pad10T">
                                <td></td>
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td id="todayprevdue">0</td>
                                <td id="todaycash">0</td>
                                <td id="todaychq">0</td>
                                <td id="todaycred">0</td>
                                <td id="todayless">0</td>
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
            <div class="form-row" style="margin-top: 70px;">
                <div class="col-md-4 col-md-offset-4">
                    <div class="text-center font-bold">
                        Total Due Collection
                    </div>
                    <table class="table table-condensed">
                        <thead>

                        </thead>
                        <tbody>
                        <tr>
                            <td>B/F</td>
                            <td id="prevtotaldue" class="font-bold">0</td>
                        </tr>
                        <tr>
                            <td>Today Due Collect</td>
                            <td id="todaycollect">0</td>
                        </tr>
                        <tr>
                            <td>Today Less</td>
                            <td id="totalless">0</td>
                        </tr>
                        <tr>
                            <td>Balance</td>
                            <td id="balance">0</td>
                        </tr>
                        <tr>
                            <td>Today Due</td>
                            <td id="newdue">0</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Total Due</td>
                            <td id="totaldue" class="font-bold">0</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            var prevduesum = 0;
            var cashsum = 0;
            var chqsum = 0;
            var credsum = 0;
            var lesssum = 0;
            var curduesum = 0;
            $('.singlerows').each(function () {
                //prevdue sum
                prevduesum += parseInt($(this).find('.prevdue').text());

                //cash sum
                cashsum += parseInt($(this).find('.cash').text());

                //cheque sum
                chqsum += parseInt($(this).find('.chq').text());

                //credit sum
                credsum += parseInt($(this).find('.credit').text());

                //less sum
                lesssum += parseInt($(this).find('.less').text());

                //current due sum
                curduesum += parseInt($(this).find('.curdue').text());

            });
            $('#todayprevdue').text(prevduesum);
            $('#todaycash').text(cashsum);
            $('#todaychq').text(chqsum);
            $('#todaycred').text(credsum);
            $('#todayless').text(lesssum);
            $('#todaydue').text(curduesum);

            $('#prevtotaldue').text(prevduesum);
            var totalcollect = cashsum + chqsum + credsum;
            $('#todaycollect').text(totalcollect);
            $('#totalless').text(lesssum);
            $('#balance').text(prevduesum - totalcollect - lesssum);
            var bfdue = parseInt($('#balance').text()) + parseInt($('#newdue').text());
            $('#totaldue').text(bfdue);
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