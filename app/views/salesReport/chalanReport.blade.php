@extends('layouts.default.default')
@section('salesReport')

    <div id="page-title">
        @if(Auth::user()->role == 'sales')
            <h3>Bill Reprint</h3>
        @else
            <h3>Chalan Report</h3>
        @endif
        @if(Auth::user()->role != 'sales')
            <div id="breadcrumb-right" class="no-print">
                <div class="float-right">
                    <a href="#" id="print" class="btn medium primary-bg" title="">
                        <span class="button-content"><i class="glyph-icon icon-list"></i> Print Chalan Report</span>
                    </a>
                </div>
            </div>
        @endif
    </div><!-- #page-title -->

    <div id="page-content">

        <div class="row">
            <div class="example-box">
                <div class="example-code">
                    @if(Auth::user()->role != 'sales')
                            <!--All Filtering(except sales) started from here -->

                    <div class="no-print">
                        <h4 class="pad10L pad5B font-bold">Filtering Options:</h4>

                        <form action="{{URL::to('chalanFiltering')}}" method="post">
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
                                                        if ($selected['shop'] == $shop->house_id)
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
                    @if(Auth::user()->role =='sales')
                            <!-- only sales filtering starts here -->
                    <div class="no-print">
                        <h4 class="pad10L pad5B font-bold">Filtering Options:</h4>

                        <form action="{{URL::to('billReprintFilter')}}" method="post">
                            <div class="form-bordered col-md-12">
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Bill ID:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-4">
                                        <input type="text" value="{{$selected['billid']}}" name="billID" id="billID">
                                    </div>

                                    <div class="form-input col-md-2">
                                        <input type="submit" class="font-white btn btn-medium primary-bg" name="submit">
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- only sales filtering ends here -->
                    @endif

                    <table class="table table-condensed" id="tableToPrint">
                        <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Main Bill No.</th>
                            <th>Chalan Bill No.</th>
                            <th>Ref. Chalan Bill</th>
                            <th>Date</th>
                            <th>Client Name</th>
                            @if(Auth::user()->role !='sales')
                                <th>Products</th>
                                <th>Quantity(piece)</th>
                                {{--<th>Gross</th>--}}
                                {{--<th>Less</th>--}}
                                {{--<th>Net</th>--}}
                                {{--<th>Payment</th>--}}
                                {{--<th>Carrying</th>--}}
                                {{--<th>Due</th>--}}
                            @endif

                            {{--@if(Auth::user()->role == 'admin' || Auth::user()->role == 'account')--}}
                                {{--<th class="no-print">Tax</th>--}}
                            {{--@endif--}}

                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'account')
                                <th class="no-print">Delete</th>
                            @endif
                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'account' || Auth::user()->role == 'sales')
                                <th class="no-print">Print</th>
                            @endif
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
                                        <td>{{$info['parent_bill']}}</td>
                                        <td>{{$info['bill_id']}}</td>
                                        <td>{{$info['ref_chalan_id']}}</td>
                                        <input name="bill_id" type="hidden" value="{{$info['bill_id']}}">
                                        <td>{{ $data=date('d/m/y', strtotime($info['bill_date']))}}</td>

                                        <td>{{$info['client_name']}}</td>

                                        @if(Auth::user()->role != 'sales')
                                            <td>
                                                @foreach($info['products'] as $product)
                                                    {{$product}}<br/>
                                                @endforeach
                                            </td>

                                            <td class="qty">
                                                @foreach($info['quantity'] as $amount)
                                                    {{round($amount,2)}}<br/>
                                                @endforeach
                                            </td>
                                        @endif
                                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'account')
                                            <td class="no-print">
                                                <input type="submit"
                                                       class="small btn bg-red float-right tooltip-button mrg10R"
                                                       value="x" data-original-title="Remove Bill"
                                                       style="width: 10px;"/>
                                            </td>
                                        @endif
                                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'account' || Auth::user()->role == 'sales')
                                            <td class="no-print">
                                                <a href="{{URL::to('printBill1office',$info['bill_id'])}}"
                                                   class="btn small bg-azure">P</a>
                                            </td>
                                        @endif

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
                                    <td></td>
                                    <td></td>
                                    <td id="todayqty">0</td>

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

            <!-- pagination ends-->
            {{--@if(Auth::user()->role != 'sales')--}}
                {{--<div class="totaltable">--}}
                    {{--<div class="col-xs-4"></div>--}}
                    {{--<div class="col-xs-4">--}}
                        {{--<div class="text-center font-bold mrg10A">--}}
                            {{--Total Sales Collection--}}
                        {{--</div>--}}
                        {{--<table class="table table-condensed">--}}
                            {{--<thead>--}}

                            {{--</thead>--}}
                            {{--<tbody>--}}
                            {{--<tr>--}}
                                {{--<td>Cash Sale</td>--}}
                                {{--<td id="cashsale">0</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>Cheque Sale</td>--}}
                                {{--<td id="chqsale">0</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>Credit card Sale</td>--}}
                                {{--<td id="credsale">0</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>Due Sale</td>--}}
                                {{--<td id="duesale">0</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td class="font-bold">Total Sales</td>--}}
                                {{--<td id="totalsale" class="font-bold">0</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--@endif--}}
        </div>
    </div>

    @if(Auth::user()->role !='sales')
        <script type="text/javascript">
            $(document).ready(function () {
                var qtysum = 0;
                var grosssum = 0;
                var netsum = 0;
                var lesssum = 0;
                var duesum = 0;
                var cashsum = 0;
                var chequesum = 0;
                var creditsum = 0;
                var totsale = 0;
                var carryingsum = 0;
                $('.singlerows').each(function () {
                    //quantity sum
                    var allqty = $(this).find('.qty').html().split('<br>');
                    allqty.forEach(function (e) {
                        var num = parseFloat(e.trim());
                        if (!isNaN(num))
                            qtysum = qtysum + num;
                    });

                    //gross sum
                    grosssum += parseFloat($(this).find('.grs').text());

                    //less sum
                    lesssum += parseFloat($(this).find('.lss').text());

                    //net sum
                    netsum += parseFloat($(this).find('.nt').text());

                    //carrying sum
                    carryingsum += parseFloat($(this).find('.carry').text());

                    //due sum
                    duesum += parseFloat($(this).find('.duebill').text());

                    //cash sum
                    var cashvar = parseFloat($(this).find('.csh').text());
                    if (!isNaN(cashvar)) {
                        cashsum += cashvar;
                        totsale += cashvar;
                    }

                    //cheque sum
                    var chqvar = parseFloat($(this).find('.chq').text());
                    if (!isNaN(chqvar)) {
                        chequesum += chqvar;
                        totsale += chqvar;
                    }

                    //credit card sum
                    var credvar = parseFloat($(this).find('.cred').text());
                    if (!isNaN(credvar)) {
                        creditsum += credvar;
                        totsale += credvar;
                    }
                });
                $('#todayqty').text(qtysum.toFixed(2));
                $('#todaygross').text(grosssum.toFixed(2));
                $('#todayless').text(lesssum.toFixed(2));
                $('#todaynet').text(netsum.toFixed(2));
                $('#todaycarrying').text(carryingsum.toFixed(2));
                $('#todaydue').text(duesum.toFixed(2));
                $('#todaypayment').html("Cash-" + cashsum.toFixed(2) + "<br/>" + "Cheque-" + chequesum.toFixed(2) + "<br/>" + "Credit-" + creditsum.toFixed(2));

                $('#cashsale').text(cashsum.toFixed(2));
                $('#chqsale').text(chequesum.toFixed(2));
                $('#credsale').text(creditsum.toFixed(2));
                $('#duesale').text(duesum.toFixed(2));
                $('#totalsale').text((totsale + duesum).toFixed(2));
            });
        </script>
        @endif

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
