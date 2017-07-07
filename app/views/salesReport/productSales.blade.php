@extends('layouts.default.default')
@section('productSales')

<div id="page-title">
	<h3>Product Sales Sheet</h3>
	<div id="breadcrumb-right" class="no-print">
		<div class="float-right">
			<a href="#" id="print" class="btn medium primary-bg" title="">
                <span class="button-content"><i class="glyph-icon icon-list"></i> Print Product Sales Sheet</span>
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

                    <form action="{{URL::to('productSalesFiltering')}}" method="post">
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
                            <div class="form-label col-md-1">
                                <label for="">
                                    Size:
                                </label>
                            </div>
                            <div class="form-input col-md-2">
                                <select id="name" name="unit_size">
                                    <option value="-1">All</option>
                                    @foreach ($units_size as $unit) 
                                        <option value="{{$unit->unit_product_size}}" 
                                            <?php 
                                                if($size_selected == $unit->unit_product_size)
                                                    echo " selected";
                                            ?>

                                        >{{$unit->unit_product_size}}</option>     
                                    @endforeach                           
                                    
                                </select>
                            </div>

                            <div class="form-label col-md-1">
                                <label for="">
                                    Group:
                                </label>
                            </div>
                            <div class="form-checkbox-radio col-md-1 mrg10T">
                                <div class="checkbox-radio">
                                    <input type="checkbox" name="grp" id="grp" <?php if(strcasecmp($grp, "on") == 0) echo 'checked'; ?> >
                                </div>
                            </div>

                            <div class="form-input col-md-1">
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
							<th class="text-center">Product Name</th>
                            <th class="text-center">Product Size</th>
                            <th class="text-center">Total Product Sale(sft)</th>
						</tr>
					</thead>
                    @if(!is_null($data))
					<tbody>
                        @foreach($data as $singleresult)
                            <tr class="singlerows">
                                <td style="padding-left: 50px;">{{$singleresult[0]}}</td>
                                <td class="text-center">{{$singleresult[2]}}</td>
                                <td class="sftamount" style="padding-left: 150px;">{{round(floatval($singleresult[1]),2)}}</td>                   
                            </tr> 
                        @endforeach
                        <tr class="font-bold font-size-16 pad10T">
                            <td class="text-center">Total</td>
                            <td></td>
                            <td class="text-center" id="totalproduct">0</td>
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
        $('.singlerows').each(function(){
           totalsft += parseFloat($(this).find('.sftamount').text());
        });
        $('#totalproduct').text(totalsft.toFixed(2));


        //print page
        $('#print').on('click',function(){
            $('#page-content-wrapper').css({'margin-left':'0px','margin-top':'-50px'});
            window.print();
            $('#page-content-wrapper').css({'margin-left':'220px','margin-top':'0px'});
        });

    });
</script>
@stop