@extends('layouts.default.default')
@section('ownerIndex')

<form action="{{URL::route('owner.store')}}" method="post">
    <div id="page-title">
        <h3>প্রোডাক্টের হিসাব নিকাশ</h3>
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
                                <th class="text-center">প্রোডাক্ট কোড</th>
                                <th class="text-center">প্রোডাক্ট সাইজ</th>
                                <th class="text-center">এল.সি.</th>
                                <th class="text-center">তারিখ</th>                            
                                <th class="text-center">পরিমাণ<br/>(এসএফটি)</th>
                                <th class="text-center">বিক্রয় পরিমাণ<br/>(এসএফটি)</th> 
                                <th class="text-center">অবশিষ্ট<br/>(এসএফটি)</th>
                                <th class="text-center">আমদানী খরচ(টাকা)</th> 
                                <!-- <th class="text-center">বিক্রয় দাম(টাকা)</th>     -->
                                <th class="text-center">মোট বিক্রয়(টাকা)</th> 
                                <th class="text-center">লাভ(টাকা)</th> 
                                <th class="text-center">বিবরণ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data))
                            <?php 
                            $x = 1;
                            ?>
                            @foreach ($data as $d)
                            <tr>
                                <td class="font-bold text-left">{{$d['product_code']}}</td>
                                <input type="hidden" value="{{$d['product_code']}}" name="<?php echo "code".$x; ?>">
                                <td>{{$d['unit_size']}}</td>
                                <td>
                                    @foreach($d['lc_number'] as $lc)
                                    {{$lc}}
                                    {{"<br/>"}}
                                    @endforeach
                                </td>
                                <td>
                                    @if(!is_null($d['lc_date']))
                                    @foreach($d['lc_date'] as $lc)
                                    {{$lc}}
                                    {{"<br/>"}}
                                    @endforeach
                                    @endif
                                </td>
                                <td>{{$d['total']}}</td>
                                <td class="s_amount">{{$d['sold_till_now']}}</td>
                                <td>{{$d['current_stock']}}</td>
                                <td style="width: 70px;"><input name = "<?php echo "buy".$x; ?>" min="0" step="any" value="{{$d['buy_rate']}}" type="number" style="width: 70px;" class="i_rate"></td>

                                <td class="total_income">{{$d['total_sale'] ? $d['total_sale'] : 0}}</td>
                                <td class="net_profit font-bold text-left">0</td>
                                <td> 

                                    
                                    <a href="{{URL::route('owner.show',$d['product_code'])}}" class="small btn primary-bg float-right tooltip-button mrg10R pad5A" data-original-title="Details">

                                        Details
                                    </a>
                                    
                            
                                </td>
                                <?php $x++;?>
                    </tr>

                    @endforeach
                    @endif
                </tbody>
            </table>


        </div>

        
    </div>
    <!-- pagination -->

    

    <!-- pagination ends--> 
</div>


</div>
</form>


<script>
$(document).ready(function(){
    $('tbody tr').each(function(){
        var sold_amount = $(this).find('.s_amount').text();

        var income_rate = $(this).find('.i_rate').val();
        if(income_rate == "")            
            income_rate = 0;

        var sold_sum = $(this).find('.total_income').text();
        var income_sum = income_rate * sold_amount;
        var n_profit = sold_sum-income_sum;

        $(this).find('.net_profit').text(n_profit);
     });

 });

</script>
@stop