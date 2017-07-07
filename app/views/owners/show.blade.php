@extends('layouts.default.default')
@section('ownerIndex')

<form action="{{URL::route('owner.store')}}" method="post">
    <div id="page-title">
        <h3>প্রোডাক্ট : {{$id}}</h3>
        @if(isset($data))
        <div id="breadcrumb-right">
            <div class="float-right">
                <input type="submit" value="Save" class="btn medium primary-bg" style="width: 150px;">
            </div>
        </div>
        @endif
    </div><!-- #page-title -->

    <div id="page-content">

        <div class="row">
            <div class="example-box">
                <div class="example-code">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center">Bill Date</th>
                                <th class="text-center">Shop</th>
                                <th class="text-center">Bill ID</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Sale Price</th>
                                <th class="text-center">Total Price</th>

                                
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($info))
                            <?php 
                            $x = 1;
                            ?>
                            @foreach ($info as $i)
                            <tr>
                            	<td class="text-center">{{ date('Y-m-d', strtotime($i['bill_date'])) }}</td>
                            	<td class="text-center">{{$i['house_name']}}</td>
                            	<td class="text-center">{{$i['bill_id']}}</td>
                            	<td class="text-center">{{$i['quantity']}}</td>
                            	<td class="text-center">{{$i['unit_sale_price']}}</td>
                            	<td class="text-center">{{$i['total_sale_price']}}</td>
                                
                    		</tr>

                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


</div>
</form>

@stop