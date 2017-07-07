@extends('layouts.default.default')
@section('returnProduct')
    <div id="page-title">
        <h3>Edit Bill</h3>
    </div><!-- #page-title -->

    <div id="page-content">
      @if(Session::has('message'))
      <div class="alert alert-success" style="margin-top: 10px;">
        <i class="fa fa-check sign"></i><strong>Success!</strong> {{Session::get('message')}}
      </div>
      @endif
        <div class="example-box">
            <div class="example-code">
                <div class="row">

                    <div class="col-lg-12">

                        <form name="get-bill" action="{{ URL::to('billUpdate') }}" method="post"
                              onsubmit="return validate();">
                            <div id="bill">
                                <div class="form-bordered">
                                    <div class="form-row">
                                        <div class="form-label col-md-2">
                                            <label for="">
                                                Bill No:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-2">
                                            <select id="bill_id" name="name" class="chosen-select">
                                                <option value="-1">--Select a bill--</option>
                                                @foreach($bills as $bill)
                                                    <option>{{$bill}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="form-label col-md-2">
                                            <label for="">
                                                Bill Date:
                                            </label>
                                        </div>
                                        <div class="form-label col-md-2">
                                            <label id="bill_date">

                                            </label>
                                        </div>
                                        <div class="form-label col-md-2">
                                            <label for="">
                                                Payment Due:
                                            </label>
                                        </div>
                                        <div class="form-label col-md-2">
                                            <label id="oldDue">
                                                0
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="bill-info">
                                <h3 class="content-box-header bg-green">
                                    <span class="">Products Information</span>
                                </h3>
                                <div class="form-bordered">

                                    <div class="single-product">
                                        <div class="form-row">
                                            <div class="form-label col-md-2">
                                                <label>
                                                    Product Code:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-2">
                                                <input type="text" min="0" name="code0" id="code" value="0"
                                                       readonly="true"/>
                                            </div>
                                            <div class="form-label col-md-2">
                                                <label>
                                                    Unit Size:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-2">
                                                <input type="text" min="0" name="size0" id="size" value=""
                                                       readonly="true"/>
                                            </div>
                                            <div class="form-label col-md-2">
                                                <label for="">
                                                    Rate(sft):
                                                </label>
                                            </div>
                                            <div class="form-input col-md-2">
                                                <input type="number" min="0" name="rate0" id="rate" value="0"
                                                       readonly="true"/>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-label col-md-2">
                                                <label>
                                                    Not Taken Yet(pcs):
                                                </label>
                                            </div>
                                            <div class="form-input col-md-2">
                                                <input type="number" min="0" name="aa0" id="aa" value="0"
                                                       readonly="true"/>
                                            </div>
                                            <div class="form-label col-md-2">
                                                <label for="" style="font-style: italic">
                                                    Release(pcs):
                                                </label>
                                            </div>
                                            <div class="form-input col-md-2">
                                                <input class="hisab" type="number" min="0" name="returnqty0"
                                                       id="returnqty" value="0" required/>
                                            </div>
                                            <div class="form-label col-md-2">
                                                <label for="" style="font-style: italic">
                                                    SFT:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-2">
                                                <input class="hisab" type="number" min="0" name="returnsft0"
                                                       id="returnsft" value="0" readonly="true" required/>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="form-row pad10A">
                                <div class="form-row col-md-4 col-md-offset-8 ">
                                    <div class="form-label col-md-8">
                                        <input type="submit" value="Submit" class="btn primary-bg medium">
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


    <script>
        function validate() {
            //console.log("on the validate");
            var selected_bill = $('#bill_id').val();
            if (selected_bill == -1) {
                alert("Please Select a Bill!");
                return false;
            }

            $('input[type=submit]').attr('disabled', true);
            return true;
        }
    </script>



    <script type="text/javascript">

        $(document).ready(function () {

            $('input[type=submit]').click(function (event) {
                //fill empty number fields with 0
                $('input[type=number]').each(function () {

                    if ($(this).val() == "") //check for NaN
                        $(this).val(0);
                });
            });

            function doIt() {
                var sum = 0;

                $('.single-product').each(function (k, v) {

                    if (k > 0) {

                        var ret = document.getElementById('returnsft' + k).value;
                        //console.log("return="+ret);

                        var rate = document.getElementById('rate' + k).value;
                        //console.log("rate="+rate);

                        sum += parseFloat(ret) * parseFloat(rate);
                    }

                });
                if (parseInt(($('#oldDue').text()) - sum) < 0) {
                    $('#back').val(sum - parseInt($('#oldDue').text()));
                    $('#due').val(0);
                } else {
                    $('#due').val(parseInt(($('#oldDue').text()) - sum));
                    $('#back').val(0);
                }

            }

            function getSize(text) {
                var s_size = text.split("X");
                var xx = s_size[0];
                var yy = s_size[1];
                var unit_sft = (xx * yy) / 144.0;
                return unit_sft;
                //console.log(unit_sft);
            }

            $('#returnqty').keyup(function (event) {
                var prod_id = event.target.id;
                var id_number = prod_id.replace(/[^\d.]/g, '');

                var prod_qty = $('#returnqty' + id_number).val();
                var unit = getSize($('#size' + id_number).val());
                //console.log(unit);
                var qty = $(this).val();
                var totsft = qty * unit;
                $('#returnsft' + id_number).val(totsft.toFixed(3));

                doIt(); //updating new Due and Moneyback
            });


            $('#bill_id').on('change', function (e) {

                e.preventDefault();

                var check = $("#bill_id").val();
                if (check == -1) {
                    $('#bill_date').text("");
                    $('#oldDue').text("0");
                    $('#due').val(0);
                    $('#back').val(0);
                    $('.single-product').slice(1).remove();
                    $('.single-product:first').show();
                    return;
                }


                var value = $("#bill_id option:selected").text();

                var billId = parseInt(value);

                var bill = {id: billId};

                $.post('rat', {id: billId, type: "chalan"}, function (data) {

                    var s = data[0]['bill_date'].split(" ");
                    $('#bill_date').text(s[0]);
                    $('#oldDue').text(data[2]);
                    $('#due').val(data[2]);

                    var totalproduct = 0;

                    $('.single-product').slice(1).remove();
                    $first = $('.single-product:first');

                    $.each(data[1], function (k, v) {
                        $first.show();
                        $('.single-product:last').clone(true).insertAfter(".single-product:last");
                        ++totalproduct;
                        $div = $('.single-product:last');
                        $div.find("*[name]").each(function () {
                            $(this).attr("name", $(this).attr("name").replace(/\d/g, "") + totalproduct);
                        });
                        $div.find("*[id]").each(function () {
                            $(this).attr("id", $(this).attr("id").replace(/\d/g, "") + totalproduct);
                        });


                        $('#code' + totalproduct).val(data[1][k]['product_code']);
                        $('#rate' + totalproduct).val(data[1][k]['unit_sale_price']);
                        $('#aa' + totalproduct).val(data[1][k]['total_piece']);


                        $.each(data[3], function (p, q) {

                            if (data[3][p]['product_code'] == data[1][k]['product_code']) {

                                $('#size' + totalproduct).val(data[3][p]['unit_product_size']);

                            }
                        });

                    });
                    $first.hide();
                })
            });
        });

    </script>

@stop
