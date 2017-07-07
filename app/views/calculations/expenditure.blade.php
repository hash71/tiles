@extends('layouts.default.default')
@section('expenditure')
<div id="page-title">
    <h3>Daily Top Sheet</h3>
    <?php $sel_house = ""; ?>
    @foreach($houses as $house)
            <?php
                if($house->house_id == $selected['house']){
                    $sel_house = $house->house_name;
                }
            ?>
    @endforeach
    <h4 class="text-center">{{$sel_house}} ({{$selected['from']}})</h4>
    <div id="breadcrumb-right" class="no-print">
        <div class="float-right">
            <a href="#" id="print" class="btn medium primary-bg" title="">
                <span class="button-content"><i class="glyph-icon icon-list"></i> Print Daily Top Sheet</span>
            </a>

        </div>
    </div>
</div><!-- #page-title -->

<div id="page-content">

    <!-- Filtering started from here -->
    <div class="no-print">
        <h4 class="pad10L pad5B font-bold">Filtering Options:</h4>

        <form action="{{URL::to('calculations/expenditureFiltering')}}" method="post">
        <div class="form-bordered col-md-12">
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Shop/Office:
                    </label>
                </div>
                <div class="form-input col-md-2">
                    <select id="name" name="house">
                        <option value="-1">-----Select an Option-----</option>
                            @foreach($houses as $house)
                                <option value="{{$house->house_id}}"
                                    <?php
                                        if($house->house_id == $selected['house']){
                                            echo " selected";
                                        }
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
                    <input type="text"  class="datepicker" name="from" value="{{$selected['from']}}">
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
            <!-- Income Table -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td></td>
                            <td>B/F</td>
                            <td class="debitamount">{{$bf}}</td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td></td>
                            <td>Today total Cash Sales</td>
                            <td class="debitamount">{{$sale}}</td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td></td>
                            <td>Today due Collection</td>
                            <td class="debitamount">{{$due_collection}}</td>
                        </tr>
                        @if(isset($incomes))
                        <?php $x=4; ?>
                            @foreach($incomes as $income)
                            <tr>
                                <td>{{$x++}}</td>
                                <td>{{$income->category}}</td>
                                <td>{{$income->description}}</td>
                                <td class="debitamount">{{$income->amount}}</td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                <!-- Income Table Ended -->

                <!-- Expense Table -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Debit</th>

                        </tr>
                    </thead>
                    <tbody>
                    <?php $x=1; ?>
                    @if(isset($expenses))
                        @foreach($expenses as $expense)
                            <tr>
                                <td>{{$x++}}</td>
                                <td>{{$expense->category}}</td>
                                <td>{{$expense->description}}</td>
                                <td class="creditamount">{{$expense->amount}}</td>
                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>
                <!-- Expense Table Ended -->

            </div>
        </div>

        <div class="form-row" style="margin-top: 70px;">
            <div class="col-md-4 col-md-offset-4">
                <div class="text-center font-bold">
                    Total Balance
                </div>
                <table class="table table-condensed">
                    <thead>

                    </thead>
                    <tbody>
                        <tr>
                            <td>Credit</td>
                            <td id="debit">0</td>
                        </tr>
                        <tr>
                            <td>Debit</td>
                            <td id="credit">0</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Today B/F</td>
                            <td id="todaybf" class="font-bold">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-row" style="margin-top: 0px;">

            <div class="col-xs-4 text-center">
              ------------------<br>
              Accountants
            </div>
            <div class="col-xs-4 text-center">
              ------------------<br>
              CEO/ED
            </div>
            <div class="col-xs-4 text-center">
              ------------------<br>
              M.D.
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var debittotal = 0;

        $('.debitamount').each(function(){
            //console.log($(this).text());
            var dbt = parseInt($(this).text());
            if(!isNaN(dbt))
                debittotal+=dbt;
        });

        $('#debit').text(debittotal);

        var credittotal = 0;

        $('.creditamount').each(function(){
            //console.log($(this).text());
            var crdt = parseInt($(this).text());
            if(!isNaN(crdt))
                credittotal+=crdt;
        });

        $('#credit').text(credittotal);

        $('#todaybf').text(debittotal-credittotal);


    });
</script>

<script type="text/javascript">
//page print script
jQuery(function($) {

    //print page
    $('#print').on('click',function(){
        $('#page-content-wrapper').css({'margin-left':'0px','margin-top':'-50px'});
        window.print();
        $('#page-content-wrapper').css({'margin-left':'220px','margin-top':'0px'});
    });
});
</script>
@stop
