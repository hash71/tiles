@extends('layouts.default.default')
@section('clientBalance')

<div id="page-title">
    <h3>Client Balance Sheet</h3>
    <div id="breadcrumb-right" class="no-print">
       <div class="float-right">
            <a href="#" id="print" class="btn medium primary-bg" title="">
            <span class="button-content"><i class="glyph-icon icon-list"></i> Print Client Balance Report</span>
            </a>       
        </div>
    </div>
</div><!-- #page-title -->

  <div id="page-content">

    <div class="row">
       <div class="example-box">
          <div class="example-code">             
            <div class="no-print">
                <h4 class="pad10L pad5B font-bold">Filtering Options:</h4>

                <form action="{{URL::to('calculations/clientBalanceFilter')}}" method="post">
                    <div class="form-bordered col-md-12">
                        <div class="form-row">
                            <div class="form-label col-md-1">
                                <label for="">
                                    Client:
                                </label>
                            </div>
                            <div class="form-input col-md-3">
                                <select id="name" name="client_name" class="chosen-select">
                                    <option value="-1">--- Select a Client ---</option>
                                    @foreach($clients as $client)
                                    <option value="{{$client->client_id}}"
                                    <?php 
                                      if($selected_client==$client->client_id)
                                        echo " selected";
                                    ?>
                                    >
                                        {{$client->client_name}}
                                        <?php echo "(".$client->mobile_number.")"?>
                                    </option>    
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-input col-md-2">
                                <input type="submit"  class="font-white btn btn-medium primary-bg" name="submit">
                            </div>

                        </div>
                    </div> <br/>
                </form>
            </div>
            <!--Filtering Ended here -->
            @if($selected_client != -1)
            <h3 class="text-center">Balance Sheet of '{{$client_name}}'</h3><br>
            @endif
            <table class="table table-condensed col-md-12" id="tableToPrint">
              <thead>
                <tr>
                  <th class="text-center">Date</th>
                  <th class="text-center">Description</th>
                  <th class="text-center">Debit</th>     
                  <th class="text-center">Credit</th>  
                  <th class="text-center">Less</th>  
                  <th class="text-center">Balance</th>           
                </tr>
              </thead>
              @if(!is_null($data))
              <tbody>
                @foreach($data as $singleresult)
                    <tr class="singlerows">
                        <td class="text-center">{{substr($singleresult[0],0,10)}}</td>
                        <td class="text-center">{{$singleresult[1]}}</td>      
                        <td class="debit text-center">{{$singleresult[2]}}</td>
                        <td class="credit text-center">{{$singleresult[3]}}</td>
                        <td class="less text-center">{{$singleresult[4]}}</td>
                        <td class="balance text-center">0</td>             
                    </tr> 
                @endforeach
                <tr class="font-bold font-size-16 pad10T">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center">Current Balance</td>
                    <td class="text-center" id="totalproduct">0</td>
                </tr>
              </tbody>
              @endif
            </table>
          </div>
        </div>
      </div>
    </div>


<script>
  $(document).ready(function(){
    //balacne calculations
    var curbalance = 0.0;
    $('.singlerows').each(function(){
      curbalance += parseFloat($(this).find('.debit').text());
      curbalance -= parseFloat($(this).find('.credit').text());
      curbalance -= parseFloat($(this).find('.less').text());
      $(this).find('.balance').text(curbalance.toFixed(2));
    });

    $('#totalproduct').text(curbalance.toFixed(2));

    //print page
    $('#print').on('click',function(){
        $('#page-content-wrapper').css({'margin-left':'0px','margin-top':'-50px'});
        window.print();
        $('#page-content-wrapper').css({'margin-left':'220px','margin-top':'0px'});
    });
  });
</script>

@stop