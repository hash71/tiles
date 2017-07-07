@extends('layouts.default.default')
@section('due_payment')
<div id="page-title">
    <h3>Due Payment</h3>
</div><!-- #page-title -->

<div id="page-content">
    <div class="example-box">
        <div class="example-code">
            <div class="row">

                <div class="col-lg-12">

                    <form name="get-bill"  action="{{ URL::to('due_transaction') }}" method="post" onsubmit="return validate();">
                        <div id="bill">
                            <div class="form-bordered">
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Bill No:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-4">
                                        <select id="bill_id" name="bill_id" class="chosen-select">
                                            <option value="-1">--- Select a name ---</option>
                                            @foreach($bills as $bill)
                                                <option>{{$bill}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="bill-info">
                            <h3 class="content-box-header bg-green">
                                <span class="">Bill Information</span>
                            </h3>
                            <div class="form-bordered">
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label>
                                            Client Name:
                                        </label>
                                    </div>
                                    <div class="form-label col-md-4">
                                        <label id = "name">

                                        </label>
                                    </div>
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Due amount:
                                        </label>
                                    </div>
                                    <div class="form-label col-md-4">
                                        <label id = "due">

                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div id="bill-total col-md-10 center-margin">
                                <h3 class="content-box-header bg-orange">
                                    <span class="product-num">Payment</span>
                                </h3>
                                <div class="form-bordered">
                                    <div class="col-md-4">
                                        <div class="form-row">
                                            <div class="form-label col-md-4">
                                                <label for="gross">
                                                    Old Due:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-8">
                                                <input type="number" min="0" step="any" name="olddue" id="olddue" value="0" readonly="true" required />
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-label col-md-4">
                                                <label for="cash">
                                                    Cash:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-8">
                                                <input type="number" min="0" step="any" name="cash" id="cash" value="" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-row">
                                            <div class="form-label col-md-4">
                                                <label for="less">
                                                    Discount:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-8">
                                                <input type="number" min="0" step="any" name="less" id="less" value="" required />
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-label col-md-4">
                                                <label for="cheque">
                                                    Cheque:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-8">
                                                <input type="number" min="0" step="any" name="cheque" id="cheque" value="" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-row">
                                            <div class="form-label col-md-4">
                                                <label for="new_due">
                                                    Payable:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-8">
                                                <input type="number" min="0" step="any" name="new_due" id="new_due" value="0" readonly="true" required />
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-label col-md-4">
                                                <label for="credit_card">
                                                    Credit Card:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-8">
                                                <input type="number" min="0" step="any" name="credit_card" id="credit_card" value="0" readonly="true" />
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-row pad10A">
                                    <div class="form-row col-md-4 col-md-offset-8 pad5A">
                                        <div class="form-label col-md-8">
                                            <input type="submit" value="Pay bill" class="btn primary-bg medium">
                                        </div>
                                        <a href="#" class="btn medium bg-gray col-md-4" title="">
                                            <span class="button-content"> Cancel</span>
                                        </a>
                                    </div>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function validate()
    {
        var selected_bill = $('#bill_id').val();
        if(selected_bill==-1)
        {
            alert("Please select a Bill!");
            return false;
        }

        $('input[type=submit]').attr('disabled',true);

        return true;
    }
</script>


<script type="text/javascript">

$(document).ready(function(){

    $('input[type=submit]').click(function(event) {
        //fill empty number fields with 0
        $('input[type=number]').each(function(){

            if($(this).val()=="") //check for NaN
                $(this).val(0);
        });
    });


    $('#less').on('keyup',function(){
        var disc = $('#less').val();
        if(disc=="")
            disc=0;

        var totaldue = $('#olddue').val() - disc;

        $('#new_due').val(totaldue);
    });




    $('#bill_id').on('change',function(e){

        e.preventDefault();

        var check = $( "#bill_id" ).val();
        if(check == -1){
            $('#name').text("");
            $('#due').text("0");

            $('#olddue').val(0);
            $('#cash').val(0);
            $('#less').val(0);
            $('#cheque').val(0);
            $('#new_due').val(0);
            $('#credit_card').val(0);

            return;
            }



        var value = $( "#bill_id option:selected" ).text();

        // var value = {a:10,b:"chaterbal"};

        var billId = parseInt(value);

        var bill = {id:billId};

            $.post('../chat',bill, function(data){

                $('#name').text(data[0]['client_name']);
                $('#due').text(data[1]);
                $('#olddue').val(data[1]);
                $('#new_due').val(data[1]);

                console.log(data);
            })
    });
});

</script>

@stop