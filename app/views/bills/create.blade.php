 @extends('layouts.default.default')
 @section('createBill')
 <div id="page-title">
    <h3>Create a Bill</h3>
</div><!-- #page-title -->

<div id="page-content">    
    <div class="example-box">
        <div class="example-code">
            <div class="row">

                <div class="col-lg-12">

                    <form name="product-insert" class="col-md-12" action="{{ URL::route('bills.store') }}" method="post">
                        <div id="lc-info">
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
                                        <select id="name" name="name">
                                            <option>--- Select a name ---</option>
                                            <option>name 1</option>
                                            <option>name 2</option>
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

                                    <div class="form-label col-md-1">
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

                        <div id="bill-total">

                            <div class="form-bordered">
                                <div class="form-row">
                                    <div class="form-label col-md-1">
                                        <label for="payment">
                                            Payment:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <select id="product" name="payment_method">
                                            <option value="cash">Cash</option>
                                            <option value="cheque">Cheque</option>
                                            <option value="credit_card">Credit card</option>
                                        </select>
                                    </div>
                                    <div class="form-label col-md-1">
                                        <label for="subtotal">
                                            Gross:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <input type="number" min="0" name="gross" id="subtotal" value="0" required />
                                    </div>

                                    <div class="form-label col-md-1">
                                        <label for="discount">
                                            Less:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <input type="number" min="0" name="less" id="discount" value="0" required />
                                    </div>

                                    <div class="form-label col-md-1">
                                        <label for="total">
                                            Net:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <input type="number" min="0" name="net" id="total" value="0" required />
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="tabs hidden-overflow">
                            <div class="single-product row">
                                <h3 class="content-box-header bg-blue-alt">
                                    <span class="product-num">Product 1</span>
                                </h3>

                                <div class="form-bordered">
                                    <div class="col-md-4">
                                        <div class="form-row">
                                            <div class="form-label col-md-5">
                                                <label for="code">
                                                    Product Code:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-7">
                                                <select id="product" name="product_code1">
                                                     @foreach ($product_code as $pc) 
                                                        <option>{{$pc}}</option>
                                                     @endforeach 
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-label col-md-5">
                                                <label for="quantity">
                                                    Quantity:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-5">
                                                <input type="number" min="0" name="product_quantity1" id="quantity1" value="0" required />
                                            </div>
                                        </div>
                                        

                                    </div>
                                   
                                    <div class="col-md-8">
                                        <div class="form-row">
                                            <div class="form-label col-md-2">
                                                <label for="">
                                                    Rate:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-3">
                                                <input type="number" min="0" name="unit_sale_price1" id="rate1" value="0" required />
                                            </div>
                                            <div class="form-label col-md-3">
                                                <label for="">
                                                    Adjust Rate:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-4">
                                                <input type="number" min="0" name="adjust_unit_price1" id="customerrate1" value="0" required />
                                            </div>
                                        </div>
 
                                        <div class="form-row">
                                            <div class="form-label col-md-2">
                                                <label for="">
                                                    Cost:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-3">
                                                <input type="number" min="0" name="cost1" id="cost" value="0" required />
                                            </div>

                                            <div class="form-label col-md-3">
                                                <label for="">
                                                    Adjust Cost:
                                                </label>
                                            </div>
                                            <div class="form-input col-md-4">
                                                <input type="number" min="0" name="extra" id="extra1" value="0" required />
                                            </div>

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
                                        <input type="submit" value="Create Bill" class="btn primary-bg medium">
                                    </div>
                                    <a href="#" class="btn medium bg-gray col-md-4" title="">
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

<script>

$(document).ready(function() {

        var totalproduct = 1;
        $('#newproduct').click(function(){

            //selecting the product_div clone it and insert it after the last product div
            $('.single-product:last').clone().insertAfter(".single-product:last");

            ++totalproduct;
            var intext = "Product "+totalproduct;
            //console.log(intext);
            $div = $('.single-product:last'); 
            
            $div.find('.product-num').html(intext);

            $div.find("*[name]").each(function() { $(this).attr("name", $(this).attr("name").replace(/\d/g, "") + totalproduct); });


        });

        $('#name').change(function(){
            $val = $('#name :selected').text();
            $index = $('#name :selected').index();
            if($index>0)
            {
                $('#custname').val($val);
            }
        });


    });
    
</script>
@stop