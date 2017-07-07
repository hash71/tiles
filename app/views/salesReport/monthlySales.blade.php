@extends('layouts.default.default')
@section('monthlySales')

<div id="page-title">
	<h3>Monthly Sales Sheet</h3>
	<div id="breadcrumb-right" class="no-print">
		<div class="float-right">
			<a href="#" id="print" class="btn medium primary-bg" title="">
                <span class="button-content"><i class="glyph-icon icon-list"></i> Print Monthly Sales Sheet</span>
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

                    <form action="{{URL::to('monthlySalesFiltering')}}" method="post">
                    <div class="form-bordered col-md-12">
                        <div class="form-row">
                            <div class="form-label col-md-1">
                                <label for="from">
                                    Month:
                                </label>
                            </div>
                            <div class="form-input col-md-2">
                                <select name="month" id="month">
                                    <option value="01" <?php if($month=='01') echo " selected"; ?> >January
                                    </option>
                                    <option value="02" <?php if($month=='02') echo " selected"; ?> >February
                                    </option>
                                    <option value="03" <?php if($month=='03') echo " selected"; ?> >March
                                    </option>
                                    <option value="04" <?php if($month=='04') echo " selected"; ?> >April
                                    </option>
                                    <option value="05" <?php if($month=='05') echo " selected"; ?> >May
                                    </option>
                                    <option value="06" <?php if($month=='06') echo " selected"; ?> >June
                                    </option>
                                    <option value="07" <?php if($month=='07') echo " selected"; ?> >July
                                    </option>
                                    <option value="08" <?php if($month=='08') echo " selected"; ?> >August
                                    </option>
                                    <option value="09" <?php if($month=='09') echo " selected"; ?> >September
                                    </option>
                                    <option value="10" <?php if($month=='10') echo " selected"; ?> >October
                                    </option>
                                    <option value="11" <?php if($month=='11') echo " selected"; ?> >November
                                    </option>
                                    <option value="12" <?php if($month=='12') echo " selected"; ?> >December
                                    </option>

                                </select>
                            </div>

                            <div class="form-label col-md-1">
                                <label for="from">
                                    Year:
                                </label>
                            </div>
                            <div class="form-input col-md-2">
                                <select name="year" id="year">
                                <?php for($i=2014;$i<=2020;$i++){ ?>
                                    <option value="<?= $i?>" <?php if($year==$i) echo " selected"; ?> ><?= $i?>
                                    </option>
                                <?php } ?>
                                </select>
                            </div>

                            <div class="form-label col-md-1">
                                <label for="">
                                    Shop:
                                </label>
                            </div>
                            <div class="form-input col-md-2">
                                <select id="name" name="shop_name">
                                    <option value="-1">All</option>

                                    @foreach($shops as $shop)
                                    <option value="{{$shop->house_id}}"

                                      <?php 
                                      if($selected_shop==$shop->house_id)
                                        echo " selected";
                                    ?>

                                    >

                                    {{$shop->house_name}}

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

				<table class="table table-condensed col-md-8 col-md-offset-2" id="tableToPrint">
					<thead>
						<tr>
							<th class="text-center">Date</th>
							<th class="text-center">Total Sales(BDT)</th>
                            <th class="text-center">Total Product Sale(sft)</th>
						</tr>
					</thead>
					<tbody>
                       
                        <?php $x=1; $empty=1;?>

                        @foreach($data as $singleresult)
                            @if($singleresult != NULL)
                                <tr class="singlerows">
                                    <td class="text-center">{{$singleresult[0]}}-{{$month}}-{{$year}}</td>
                                    <td class="salesamount text-center">{{sprintf("%.2f",$singleresult[1])}}</td>
                                    <td class="sftamount text-center">{{sprintf("%.2f",$singleresult[2])}}</td>                     
                                </tr> 
                            <?php $empty=0;?>
                            @endif
                            <?php $x++; ?>
                        @endforeach
                        @if(!$empty)
                        <tr class="font-bold font-size-16 pad10T">
                            <td class="text-center">Total</td>
                            <td class="text-center" id="totalsale">0</td>
                            <td class="text-center" id="totalproduct">0</td>
                        </tr>
						@endif
                        

					</tbody>
				</table>
			</div>


		</div>
	</div>


</div>

<script type="text/javascript">
    $(document).ready(function(){
        var totalsales = 0.0;
        var totalsft = 0.0;
        $('.singlerows').each(function(){
           totalsales += parseFloat($(this).find('.salesamount').text());

           totalsft += parseFloat($(this).find('.sftamount').text());
        });
        $('#totalsale').text(totalsales.toFixed(2));
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