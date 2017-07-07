 @extends('layouts.default.default')
 @section('createBill')
 <div id="page-title">
    <h3>Create a Bill</h3>
</div><!-- #page-title -->

<div id="page-content">
  @if(Session::has('message'))
  <div class="alert alert-danger" style="margin-top: 10px;">
    <i class="fa fa-time sign"></i><strong>Error!</strong> {{Session::get('message')}}
  </div>
  @endif
    <div class="example-box">
        <div class="example-code">
            <form action="{{ URL::route('bills.store') }}" method="post" onsubmit="return validate();">
                <div id="form-wizard" class="form-wizard">
                    <ul>
                        <li>
                            <a href="#step-1">
                              <label class="wizard-step">1</label>
                              <span class="wizard-description">
                                 Customer information
                                 <small>Customer information details</small>
                              </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-2">
                              <label class="wizard-step">2</label>
                              <span class="wizard-description">
                                 Product information
                                 <small>Confirm product details</small>
                              </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-3">
                              <label class="wizard-step">3</label>
                              <span class="wizard-description">
                                 Payment
                                 <small>Cost calculation and payment</small>
                              </span>
                            </a>
                        </li>
                    </ul>

                    <div id="step-1">
                        <div class="lc-info col-md-10 center-margin">
                            <h3 class="content-box-header bg-green">
                                <span class="">Customer Information</span>
                            </h3>
                            <div class="form-bordered">
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Customer Name:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-4">
                                        <select id="name" name="name" class="chosen-select">
                                            <option value="-1">--- Select client ---</option>
                                            @foreach($clients as $client)
                                            <option value="{{$client->client_id}}">
                                                {{$client->client_name}}
                                                <?php echo "( ".$client->mobile_number." )"?>
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-input col-md-6">
                                        <input type="text" name="client_name" id="custname" value="" required />
                                    </div>

                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Mobile No.:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-3">
                                        <input type="text" id="mobile" name="mobile_number" value="" required>
                                    </div>

                                    <div class="form-label col-md-1" style="padding: 10px 0px;">
                                        <label for="">
                                            Email:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-6">
                                        <input type="email" name="client_email" id="email" value=""/>
                                    </div>
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Address:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        <input type="text" name="address" id="address" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="step-2">
                        <div class="col-md-12 center-margin">
                        <div class="tabs hidden-overflow">
                            <div class="single-product row">
                                <h3 class="content-box-header bg-blue-alt">
                                    <span class="product-num">Product 1</span>
                                    <a href="#" class="remove large btn bg-red float-right tooltip-button mrg10R" data-placement="left" title="" data-original-title="Remove Product" style="margin-top:2px;" id="remove1"><i class="glyph-icon icon-remove"></i></a>
                                </h3>

                                <div class="form-bordered">
                                    <div class="form-row">
                                        <div class="form-label col-md-2">
                                            <label for="code">
                                                Product Code:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-2">
                                            <input name="product_code1" type="text" id="autocomplete1">

                                        </div>
                                        <div class="form-label col-md-2">
                                            <label for="quantity">
                                                Quantity(sft):
                                            </label>
                                        </div>
                                        <div class="form-input col-md-2">
                                            <input type="number" class="sft_q" min="0" name="product_quantity1" id="quantity1" value="" required />
                                        </div>
                                        <div class="form-label col-md-2">
                                            <label for="">
                                                Piece:
                                            </label>
                                        </div>
                                        <div class="form-label col-md-2">
                                            <label id="piece1">

                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label col-md-1">
                                            <label for="">
                                                Rate:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-2">
                                            <input type="number" min="0" step="any" name="unit_sale_price1" id="rate1" value="" required />
                                        </div>
                                        <div class="form-label col-md-1">
                                            <label for="">
                                                Cost:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-2">
                                            <input type="number" min="0" step="any" name="cost1" id="cost1" value="0" readonly="true" required />
                                        </div>

                                        <div class="form-label col-md-1">
                                            <label for="">
                                                C. Rate:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-2">
                                            <input type="number" min="0" step="any" name="adjust_unit_price1" id="customerrate1" value="" required />
                                        </div>
                                        <div class="form-label col-md-1">
                                            <label for="">
                                                C. Cost:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-2">
                                            <input type="number" min="0" step="any" name="extra" id="extra1" value="0" readonly="true" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="form-row pad10A">
                                <a href="javascript:;" class="btn medium bg-orange col-md-2" title="" id="newproduct">
                                    <span class="button-content"><i class="glyph-icon icon-plus"></i> Add another product</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div id="step-3">
                        <div id="bill-total col-md-10 center-margin">
                            <h3 class="content-box-header bg-orange">
                                <span class="product-num">Total Cost</span>
                            </h3>
                            <div class="form-bordered">
                                <div class="col-md-4">
                                    <div class="form-row">
                                        <div class="form-label col-md-6">
                                            <label for="gross">
                                                Gross:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-6">
                                            <input type="number" min="0" step="any" name="gross" id="gross" value="0" readonly="true" required />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label col-md-6">
                                            <label for="gross">
                                                Customer Gross:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-6">
                                            <input type="number" min="0" step="any" name="custgross" id="custgross" value="0" readonly="true" required />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label col-md-6">
                                            <label for="gross">
                                                Cash:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-6">
                                            <input type="number" min="0" step="any" name="cash" id="cash" value="" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-row">
                                        <div class="form-label col-md-6">
                                            <label for="discount">
                                                Discount:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-6">
                                            <input type="number" min="0" step="any" name="less" id="discount" value="" required />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label col-md-6">
                                            <label for="discount">
                                                Customer Discount:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-6">
                                            <input type="number" min="0" step="any" name="custless" id="custdiscount" value="0" required />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label col-md-6">
                                            <label for="cheque">
                                                Cheque:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-6">
                                            <input type="number" min="0" step="any" name="cheque" id="cheque" value="" required />
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-row">
                                        <div class="form-label col-md-6">
                                            <label for="total">
                                                Total:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-6">
                                            <input type="number" min="0" step="any" name="net" id="total" value="0" readonly="true" required />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label col-md-6">
                                            <label for="total">
                                                Customer Total:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-6">
                                            <input type="number" min="0" step="any" name="custnet" id="custtotal" value="0" readonly="true" required />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label col-md-6">
                                            <label for="creditcard">
                                                Credit Card:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-6">
                                            <input type="number" min="0" step="any" name="credit_card" id="creditcard" value="0" readonly="true" />
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="form-row">
                                        <div class="form-label col-md-6">
                                            <label for="carryingcost">
                                                Carrying Cost:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-6">
                                            <input type="number" min="0" step="any" name="carryingcost" id="carryingcost" value="0" required />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div><!-- #page-content -->

<script>
var remainingprod = <?php echo json_encode($remain_at_stock); ?>;

function validate() {
    var flag= true;
    var name_val = $('#custname').val();
    if(name_val=="")
    {
        alert('Please select an Client!');
        return false;
    }

    $('.productlist').each(function(){
        var p_val = $(this).val();
        if(p_val<0)
        {
            flag=false;
        }
    });


    if(flag==false)
    {
        alert('Please select a Product!');
    }

    $warning = '<div class="warning" style="color: red; font-size: 10px;">Not enough product in stock</div>';

    var enough_product = true;
    $('.single-product').each(function(index){
        $product = $(this).find('.ui-autocomplete-input').val().split("(")[0];
        $product_amount = $(this).find('.sft_q').val();

        // console.log($product_amount);
        // console.log($product);
        // console.log(remainingprod[$product]);

        $(this).find('.warning').remove();

        if($product_amount > remainingprod[$product])
        {
          $(this).find('.sft_q').closest('div').append($warning);
          enough_product = false;
        }
    });

    if(enough_product == false)
    {
      //#step-2 click
      $('#form-wizard li:eq(1) a').click();

      return false;
    }

    //check for double click
    if(flag == true)
    {
        $('.buttonFinish').addClass('disabled');
    }

    return flag;
}

</script>

<script>

    var autocompletefunc = null;
    $(document).ready(function(){

        $('input[type=submit]').click(function(event) {
            //fill empty number fields with 0
            $('input[type=number]').each(function(){

                if($(this).val()=="") //check for NaN
                    $(this).val(0);
            });
        });

        autocompletefunc = function(elem){
            var prodlist=[
            <?php
            foreach ($product_code as $pc)
            {
                echo '"'.$pc;
                foreach($product_size as $size)
                {
                    if($pc == $size->product_code)
                        echo '('.$size->unit_product_size.')",';
                }
            }
            ?>
            ];

            elem.autocomplete({
                autoFocus:true,
                source: function(request, response) {
                    var results = $.ui.autocomplete.filter(prodlist, request.term);

                    response(results.slice(0, 5));
                }

            });
        }

        autocompletefunc($('#autocomplete1'));

        function totalcount(){
            var disc = $('#discount').val();
            var grossval = $('#gross').val();
            if(disc=="")
                disc = 0;
            var nettotal = parseFloat(grossval)-parseFloat(disc);
            $('#total').val(nettotal);
        }

        function custtotalcount(){
            var disc = $('#custdiscount').val();
            var grossval = $('#custgross').val();
            if(disc=="")
                disc = 0;
            var nettotal = parseFloat(grossval)-parseFloat(disc);
            $('#custtotal').val(nettotal);
        }

        function getSize(text){
            var tmp = text.split("(");
            var p_size = tmp[1].split(")");
            var s_size = p_size[0].split("X");
            var xx = s_size[0];
            var yy = s_size[1];
            var unit_sft = (xx*yy)/144.0;
            return unit_sft;
            //console.log(unit_sft);
        }

        //sft to piece conversion
        //bind event to sft input field
        $('body').on('keyup','.sft_q', function(event){
            var prod_id = event.target.id;
            var id_number = prod_id.replace( /[^\d.]/g,'');
            //console.log(id_number);
            var productName = $('#autocomplete'+id_number);
            if(productName.val()!="")
            {
                var unit = getSize(productName.val());
                var total_sft = $(this).val();
                var pis = total_sft/unit;
                pis = pis.toFixed(3);
                var total_pis = Math.ceil(pis);
                $('#piece'+id_number).text(pis+" = "+total_pis);
            }
        });

        //single products sum cost calculation
        $('body').on('keyup','.single-product input[type=number]',function(){

            var i=1;
            var totalcost = 0;
            var custtotalcost = 0;

            $(".single-product").each(function(){
                var qvalue = $('#quantity'+i).val();
                if(qvalue=="")
                    qvalue = 0;
                var ratevalue = $('#rate'+i).val();
                if(ratevalue=="")
                    ratevalue = 0;
                var custrate = $('#customerrate'+i).val();
                if(custrate=="")
                    custrate = 0;

                var singletotal = parseFloat(qvalue) * parseFloat(ratevalue);
                var custtotal = parseFloat(qvalue) * parseFloat(custrate);

                $('#cost'+i).val(singletotal);
                $('#extra'+i).val(custtotal);


                i++;
                totalcost+=parseFloat(singletotal);
                custtotalcost+=parseFloat(custtotal);

            });

            $('#gross').val(totalcost);
            $('#custgross').val(custtotalcost);
            totalcount();
            custtotalcount();
        });

        //Gross - discount = total calculation at total cost page
        $('#discount').on('keyup',totalcount);

        //CustomerGross - discount = customertotal calculation at total cost page
        $('#custdiscount').on('keyup',custtotalcount);


        var totalproduct = 1;
        $('#newproduct').click(function(){
            //fixing height bug for dynamically generated 'stepContainer' element
            $('.stepContainer').removeAttr('style');

            //selecting the product_div clone it and insert it after the last product div
            $('.single-product:last').clone().insertAfter(".single-product:last");

            ++totalproduct;
            var intext = "Product "+totalproduct;
            //console.log(intext);
            $div = $('.single-product:last');

            $div.find('.product-num').html(intext);

            $div.find("*[name]").each(function() { $(this).attr("name", $(this).attr("name").replace(/\d/g, "") + totalproduct); });
            $div.find("*[id]").each(function() { $(this).attr("id", $(this).attr("id").replace(/\d/g, "") + totalproduct); });

            //Setting newly added field input value 0
            $(".single-product:last input[type=number]").each(function(){
                $(this).val();
            });
            $(".single-product:last #piece"+totalproduct).text("");
            $(".single-product:last #autocomplete"+totalproduct).val("");

            autocompletefunc($('#autocomplete'+totalproduct));

        });


        //product remove
        $('body').on('click','.remove',function(event){

            //If there is only one product then that product can't be deleted
            if(totalproduct<=1)
            {
                alert("Only one product can't be deleted");
                return ;
            }
            var elemid = $(this).attr("id");
            var par = $('#'+elemid).closest('.single-product');
            par.remove();
            --totalproduct;

            //update other products
            var productcount = 1;
            var totalcost = 0;
            var custtotalcost = 0;
            $('.single-product').each(function() {
                var intext = "Product "+productcount;
                //alert(intext);

                $(this).find('.product-num').html(intext);

                $(this).find("*[name]").each(function() { $(this).attr("name", $(this).attr("name").replace(/\d/g, "") + productcount); });
                $(this).find("*[id]").each(function() { $(this).attr("id", $(this).attr("id").replace(/\d/g, "") + productcount); });
                var singletotal = $('#cost'+productcount).val();
                var custtotal = $('#extra'+productcount).val();

                totalcost+=parseFloat(singletotal);
                custtotalcost+=parseFloat(custtotal);
                productcount++;
            });
            //updating total cost
            $('#gross').val(parseFloat(totalcost));
            $('#custgross').val(parseFloat(custtotalcost));
            totalcount();
            custtotalcount();

            //fixing height bug for dynamically generated 'stepContainer' element
            $('.stepContainer').removeAttr('style');

        });
    });

</script>

<script type="text/javascript">

    $(document).ready(function(){

        $('#name').on('change',function(e){

            e.preventDefault();

            var value = $( "#name" ).val();

            if(value == -1){
                $('#custname').val("");
                $('#mobile').val("");
                $('#email').val("");
                $('#address').val("");
                return;
            }

    // var value = {a:10,b:"chaterbal"};

    var clientId = parseInt(value);

    var client = {id:clientId};

    // console.log(client);


    $.post('../kat',client, function(data){

        // $('#bal').text(data[0]['client_name']);
        // $('#due').text(data[1]);

        $('#custname').val(data[0]['client_name']);
        $('#mobile').val(data[0]['mobile_number']);
        $('#email').val(data[0]['client_email']);
        $('#address').val(data[0]['address']);
        console.log(data);
    })
});
});

</script>
@stop
