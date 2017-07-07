@extends('layouts.default.default')
@section('clientSales')

<div id="page-title">
	<h3>Client Sales Sheet</h3>
	<div id="breadcrumb-right" class="no-print">
		<div class="float-right">
			<a href="#" id="print" class="btn medium primary-bg" title="">
                <span class="button-content"><i class="glyph-icon icon-list"></i> Print Client Sales Sheet</span>
            </a>       
		</div>
	</div>
</div><!-- #page-title -->

<div id="page-content">

	<div class="row">
		<div class="example-box">
			<div class="example-code">
				<!-- Filtering started from here -->
                <div class="no-print">
                    <h4 class="pad10L pad5B font-bold">Filtering Options:</h4>

                    <form action="{{URL::to('clientSalesFiltering')}}" method="post">
                    <div class="form-bordered col-md-12">
                        <div class="form-row">
                            <div class="form-label col-md-1">
                                <label for="from">
                                    From:
                                </label>
                            </div>
                            <div class="form-input col-md-2">
                                <input type="text"  class="fromDate datepicker" name="from" value="{{$selected['from']}}">
                            </div>

                            <div class="form-label col-md-1">
                                <label for="to">
                                    To:
                                </label>
                            </div>
                            <div class="form-input col-md-2">
                                <input type="text"  class="toDate datepicker" name="to" value="{{$selected['to']}}">
                            </div>

                            <div class="form-input col-md-2">
                                <input type="submit"  class="font-white btn btn-medium primary-bg" name="submit">
                            </div>

                        </div>
                    </div> <br/>
                    </form>
                </div>
                <!--Filtering Ended here -->

				<table class="table table-condensed col-md-8 col-md-offset-2" id="tableToPrint">
					<thead>
						<tr>
							<th class="text-center">Client Name</th>
                            <th class="text-center">Total Product Sale(sft)</th>
                            <th class="text-center">Total Bill(TK)</th>      
						</tr>
					</thead>
                    @if(!is_null($data))
					<tbody>
                        @foreach($data as $singleresult)
                            <tr class="singlerows">
                                <td class="text-center">{{$singleresult[0]}}</td>
                                <td class="sftamount text-center">{{floatval($singleresult[1])}}</td> 
                                <td class="billamount text-center">{{$singleresult[2]}}</td>            
                            </tr> 
                        @endforeach
                        <tr class="font-bold font-size-16 pad10T">
                            <td class="text-center">Total</td>
                            <td class="text-center" id="totalproduct">0</td>
                            <td class="text-center" id="totalbill">0</td>
                        </tr>
					</tbody>
                    @endif
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var totalsft = 0.0;
        var totalbill = 0.0;
        $('.singlerows').each(function(){
           totalsft += parseFloat($(this).find('.sftamount').text());
           totalbill += parseFloat($(this).find('.billamount').text());
        });
        $('#totalproduct').text(totalsft.toFixed(2));
        $('#totalbill').text(totalbill.toFixed(2));

        //print page
        $('#print').on('click',function(){
            $('#page-content-wrapper').css({'margin-left':'0px','margin-top':'-50px'});
            window.print();
            $('#page-content-wrapper').css({'margin-left':'220px','margin-top':'0px'});
        });
    });
</script>
@stop