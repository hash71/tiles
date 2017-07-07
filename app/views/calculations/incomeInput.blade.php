@extends('layouts.default.default')
@section('incomeInput')
<div id="page-title">
    <h3>Income</h3>
</div><!-- #page-title -->

<div id="page-content">
    <div class="example-box">
        <div class="example-code">
            <div class="row">

                <div class="col-lg-12">
                    <form name="get-bill"  action="{{URL::to('store_income')}}" method="post" onsubmit="return validate();">
                        <div id="bill-total col-md-10 center-margin">
                            <h3 class="content-box-header bg-green">
                                <span class="product-num">Income Description</span>
                            </h3>
                            <div class="form-bordered">
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="gross">
                                            Income:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-4">
                                        <select id="name" name="category" class="chosen-select">
                                            <option value="-1">-------Select Option-------</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category}}">{{$category}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="bankname">
                                        <div class="form-label col-md-2">
                                            <label for="gross">
                                                Bank Name:
                                            </label>
                                        </div>
                                        <div class="form-input col-md-4">
                                            <select id="name" name="bank" class="chosen-select">
                                                <option value="-1">-------Select Option-------</option>
                                                @foreach($bank_name as $bank)
                                                    <option value="{{$bank}}">{{$bank}}</option>

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="gross">
                                            Description:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        <input type="text" name="description" id="descrip" value=""  />
                                    </div>
                                    </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="gross">
                                            Amount:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <input type="number" min="0" step="any" name="amount" id="amount" value="0" required />
                                    </div>
                                    <div class="form-label col-md-1">
                                        <label for="gross">
                                            Shop:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-3">
                                        <select id="house" name="house" class="chosen-select">
                                            <option value="-1">-------Select Option-------</option>
                                            @foreach($houses as $house)
                                                <option value="{{$house->house_id}}">{{$house->house_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row pad10A">
                                <div class="form-row col-md-4 col-md-offset-8 ">
                                    <div class="form-label col-md-8">
                                        <input type="submit" value="Add Income" class="btn primary-bg medium">
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

    var name_val = $('#name').val();
    var shop_name = $('#house').val();

    if(name_val == -1)
    {
        alert('Please select an Income Category!');
        return false;
    }
    else if(shop_name == -1)
    {
        alert('Please select an Shop!');
        return false;
    }
    else
    {
    	$('input[type=submit').attr('disabled', true);

    	return true;
    }

}


$(document).ready(function(){
    $('#bankname').hide();
    $('#name').change(
        function(){
            var selected_val = $('#name option:selected').val();
            console.log(selected_val);

            var index = selected_val.search(/bank/i); //case insensitive regex word search
            //console.log(index);
            if(index<0)
                $('#bankname').hide();
            else
                $('#bankname').show();
        }
    );
});

</script>
@stop