@extends('layouts.default.default')
@section('bankBalance')

<div id="page-title">
    <h3>Bank Balance Sheet</h3>
    <div id="breadcrumb-right" class="no-print">
       <div class="float-right">
            <a href="#" id="print" class="btn medium primary-bg" title="">
            <span class="button-content"><i class="glyph-icon icon-list"></i> Print Bank Balance Report</span>
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

                <form action="{{URL::to('calculations/bankBalanceFilter')}}" method="post">
                    <div class="form-bordered col-md-12">
                        <div class="form-row">
                            <div class="form-label col-md-1">
                                <label for="">
                                    Bank:
                                </label>
                            </div>
                            <div class="form-input col-md-3">
                                <select id="name" name="bank_name" class="chosen-select">
                                    <option value="-1">--- Select a bank ---</option>
                                    @foreach($banks as $bank)
                                    <option value="{{$bank->name}}"
                                    <?php 
                                      if($selected_bank==$bank->name)
                                        echo " selected";
                                    ?>
                                    >
                                        {{$bank->name}}
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
            @if($selected_bank != -1)
            <h3 class="text-center">Balance Sheet of '{{$selected_bank}}'</h3><br>
            @endif
            <table class="table table-condensed col-md-12" id="tableToPrint">
              <thead>
                <tr>
                  <th class="text-center">Date</th>
                  <th class="text-center">Description</th>
                  <th class="text-center">Debit</th>     
                  <th class="text-center">Credit</th>  
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
                        <td class="balance text-center">{{$singleresult[4]}}</td>             
                    </tr> 
                @endforeach
                <tr class="font-bold font-size-16 pad10T">
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
    
  });
</script>

@stop