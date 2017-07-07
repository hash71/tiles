@extends('layouts.default.default')

@section('createProduct')

<div id="page-title">
<h3>Insert New Products</h3>
</div><!-- #page-title -->

<div id="page-content">
<div class="example-box">
    <div class="example-code">
        <div class="row">

            <div class="col-lg-12">
              @if(Session::has('success'))
              <div class="infobox infobox-close-wrapper success-bg" style="margin-bottom: 10px;">
                  <a href="#" title="Close Message" class="glyph-icon infobox-close icon-remove"></a>
                  <h4 class="infobox-title">Great!</h4>
                  <p>{{Session::get('success')}}</p>
              </div>
              @endif

                <form name="product-insert" class="col-md-12" action="{{URL::route('products.store')}}" method="post" onsubmit="return validate();">
                    <div id="lc-info">
                        <h3 class="content-box-header bg-blue-alt">
                            <span class="">LC Information</span>
                        </h3>

                        <div class="form-bordered">
                            <div class="form-row">
                                <div class="form-label col-md-1">
                                    <label for="">
                                        LC No.:
                                    </label>
                                </div>
                                <div class="form-input col-md-3">
                                    <input type="text" name="lc_number" id="lcnumber" value="" required />
                                </div>

                                <div class="form-label col-md-1">
                                    <label for="">
                                        Date:
                                    </label>
                                </div>
                                <div class="form-input col-md-3">
                                    <input type="text" class="datepicker" id="lcdate" name="lc_date" required />
                                </div>

                                <!-- <div class="form-label col-md-1">
                                    <label for="">
                                        Cost:
                                    </label>
                                </div>
                                <div class="form-input col-md-3">
                                    <input type="number" name="lc_cost" id="lccost" value="" min="0" required />
                                </div> -->
                            </div>

                        </div>
                    </div>

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
                                    <div class="form-input col-md-3">
                                        <input type="text" name="product_code1" id="code1" value="" required />
                                    </div>
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Unit Size:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">

                                        <input type="number" min="0" step="any" name="first_unit_product_size1" id="asize1" value="" required />
                                    </div>
                                    <div class="form-input col-md-1" style="padding: 15px 0px; width: 10px;"> X </div>
                                    <div class="form-input col-md-2">
                                        <input type="number" min="0" step="any" name="second_unit_product_size1" id="bsize1" value="" required />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="quantity">
                                            Quantity (pieces):
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <input type="number" min="0" name="quantity1" id="quantity1" value="0" required />
                                    </div>
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Per Box (pieces):
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <input type="number" min="0" name="perbox1" id="perbox1" value="0" class="boxamount" required />
                                    </div>
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Wastage (pieces):
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <input type="number" min="0" name="wastage_before_stock1" id="wastage1" value="0" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-row pad10A">
                            <a href="javascript:;" class="btn medium bg-orange col-md-2" title="" id="newproduct">
                                <span class="button-content"><i class="glyph-icon icon-plus"></i> Add another product</span>
                            </a>
                            <div class="form-row col-md-4 col-md-offset-6 ">
                                <div class="form-label col-md-8">
                                    <input type="submit" value="Save All Products" class="btn primary-bg medium">
                                </div>

                                <a href="{{ URL::route('products.index') }}" class="btn medium bg-gray col-md-4" title="">
                                    <span class="button-content"> Cancel</span>
                                </a>
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
        var flag = true;
        $('.boxamount').each(function(){
            if($(this).val()=='' || $(this).val()==0)
            {
                $(this).focus();
                alert('Please insert value in Box field');

                flag=false;
                return false;
            }
        });

        if(flag==true)
        {
        	$('input[type=submit').attr('disabled', true);
        }

        return flag;
    }


</script>

<script>

$(document).ready(function() {

    var totalproduct = 1;
    $('#newproduct').click(function(){

        //selecting the product_div clone it and insert it after the last product div
        $('.single-product:last').clone(true).insertAfter(".single-product:last");

        ++totalproduct;
        var intext = "Product "+totalproduct;
        //console.log(intext);
        $div = $('.single-product:last');
        $div.find('.product-num').html(intext);

        $div.find("*[name]").each(function() { $(this).attr("name", $(this).attr("name").replace(/\d/g, "") + totalproduct); });
        $div.find("*[id]").each(function() { $(this).attr("id", $(this).attr("id").replace(/\d/g, "") + totalproduct); });

        //Setting newly added field input value 0
        $(".single-product:last input[type=number]").each(function(){
            $(this).val(0);
        });

        $(".single-product:last input[type=text]").each(function(){
            $(this).val("");
        });

        $('#asize'+totalproduct).val("");
        $('#bsize'+totalproduct).val("");


    });


    //product remove
    $('.remove').click(function(event){

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
        $('.single-product').each(function() {
            var intext = "Product "+productcount;
            //alert(intext);

            $(this).find('.product-num').html(intext);

            $(this).find("*[name]").each(function() { $(this).attr("name", $(this).attr("name").replace(/\d/g, "") + productcount); });
            $(this).find("*[id]").each(function() { $(this).attr("id", $(this).attr("id").replace(/\d/g, "") + productcount); });

            productcount++;
        });



    });



});

</script>

@stop
