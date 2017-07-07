 @extends('layouts.default.default')
 @section('print_bill')
 <div id="page-title">
    <h3>Print Bill</h3>
</div><!-- #page-title -->

<div id="page-content">
    <div class="example-box">
        <div class="example-code">

                <div class="form-row">

                    <form target="_blank" action="{{URL::to('print2')}}" method="post" class="col-md-3 col-md-offset-3">
                        <input type="submit" class="btn x-large primary-bg" value="Customer Copy">
                        <input name = "bill_for" type="hidden" value="Customer Copy">
                    </form>
                    <form target="_blank" action="{{URL::to('print2')}}" method="post" class="col-md-3">
                        <input type="submit" class="btn x-large bg-orange" value="Office Copy">
                        <input name = "bill_for" type="hidden" value="Office Copy">
                    </form>
                </div>

                <div class="form-row" style="margin-top: 70px;">
                    <a href="{{URL::to('bills/duePayment')}}" class="col-md-4 col-md-offset-4 btn x-large bg-blue">Create Another Bill</a>
                </div>
        </div>
    </div>
</div>
@stop
