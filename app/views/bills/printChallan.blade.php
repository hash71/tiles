@extends('layouts.default.default')
@section('print_bill')
<div id="page-title">
   <h3>Print Challan</h3>
</div><!-- #page-title -->

<div id="page-content">
   <div class="example-box">
       <div class="example-code">
               <div class="form-row">

                   <a href="{{URL::to('print3/'.$challan_id)}}" target="_blank" class="col-md-2 col-md-offset-3 btn x-large primary-bg">Customer Copy</a>
                   <a href="{{URL::to('print3/'.$challan_id)}}" target="_blank" class="col-md-2 btn x-large bg-orange">Shop Copy</a>
                   <a href="{{URL::to('print3/'.$challan_id)}}" target="_blank" class="col-md-2 btn x-large bg-azure">Office Copy</a>

               </div>

               <div class="form-row" style="margin-top: 70px;">
                   <a href="{{URL::to('chalan/create')}}" class="col-md-4 col-md-offset-4 btn x-large bg-blue">Create Another Bill</a>
               </div>
       </div>
   </div>
</div>
@stop
