@extends('layouts.default.default')

@section('employee_payment')

<div id="page-title">
    <h3>Employee payment</h3>
</div><!-- #page-title -->

<div id="page-content">
    <div class="example-box">
        <div class="example-code">
            <div class="row">

                <div class="col-lg-12">


                    <form action="{{URL::to('employee_payment_expense')}}" method="post" onsubmit="return validate();">
                        <div id="bill-total col-md-10 center-margin">
                            <h3 class="content-box-header bg-green">
                                <span class="product-num">Payment</span>
                            </h3>
                            <div class="form-bordered">
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="gross">
                                            Employee:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-4">
                                        <select id="name" name="employee" data-placeholder="---choose an employee---" class="chosen-select">
                                            <option value="-100"></option>
                                        	@foreach ($employees as $employee)
                                                <option value="{{$employee->id}}">{{$employee->name." (".$employee->designation.")"}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                    <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="gross">
                                            Amount:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-2">
                                        <input type="number" min="0" step="any" name="amount" id="amount" value="0" required  />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row pad10A">
                                <div class="form-row col-md-4 col-md-offset-8 ">
                                    <div class="form-label col-md-8">
                                        <input type="submit" value="Add payment" class="btn primary-bg medium">
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
    var amount_val = $('#amount').val();
    if(name_val == -100)
    {
        alert('Please select an Employee!');
        return false;
    }
    if(amount_val == "" || amount_val==0)
    {
        alert("Payment amount can't be Empty or 0");
        return false;
    }
    return true;
}

</script>


@stop