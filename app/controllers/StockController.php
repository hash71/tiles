<?php

class StockController extends \BaseController
{

    /** Stock pending page.
     * Showing bills which are paid and chalan is delivered but not cleared from stock.
     * From this page that bills could be cleared
     **/
    public function index()
    {
        //nazmul vai start
//        $bill_info = Bill::where('stock_clear', 0)->get();
//
//        $i = 0;
//
//        foreach ($bill_info as $bill) {
//            $data[$i]['bill_id'] = $bill->bill_id;
//            $data[$i]['bill_date'] = $bill->bill_date;
//
//            $data[$i]['client_name'] = Client::where('client_id', $bill->client_id)->pluck('client_name');
//
//            $data[$i]['products'] = BillProduct::where('bill_id', $bill->bill_id)->lists('product_code');
//
//            $data[$i]['quantity'] = BillProduct::where('bill_id', $bill->bill_id)->lists('total_piece');
//
//            $i++;
//        }
        //nazmul vai end

        return View::make('stock.stockPending');
    }


    /** Bill clear post submit request handler **/
//nazmul_vai start
//    	public function stockClear()
//    	{
//    		//Get the bill whose product should be cleared from stock
//    		Bill::find(Input::get('clear_id'))
//    			->update(['stock_clear'=>1]);
//
//    		//Get the products sold on that bill
//    		$products = BillProduct::where('bill_id',Input::get('clear_id'))->get();
//
//    		//update stock for all those products
//    		foreach ($products as $product)
//    		{
//    			$updated = ProductTracker::where('product_code',$product['product_code'])
//    						->pluck('total_sold_unit')+$product['total_piece'];
//
//    			ProductTracker::where('product_code',$product['product_code'])
//    				->update(['total_sold_unit'=>$updated]);
//    		}
//
//    		return Redirect::to('stockPending');
//    	}


    public function stockClear()
    {
        //Get the bill whose product should be cleared from stock

        $chalan_id = Input::get('clear_id');

        DB::table('chalan_parent')
            ->where('id', $chalan_id)
            ->update(['clear' => 1]);

        //Get the products sold on that bill
        $products = DB::table('chalan_product')->where('chalan_id', $chalan_id)->get();

        //update stock for all those products
        foreach ($products as $product) {
            $updated = ProductTracker::where('product_code', $product->product_id)->pluck('total_sold_unit') + $product->total_piece;

            ProductTracker::where('product_code', $product->product_id)
                ->update(['total_sold_unit' => $updated]);


            /** owner table insert **/
            $totalSale = DB::table('owner_calculation')->where('product_code', $product->product_id)->pluck('total_sale');

            $unit_price = DB::table('bill_product')
                ->where('bill_id',
                    DB::table('chalan_parent')
                        ->where('id', Input::get('clear_id'))->pluck('parent_bill')
                )->pluck('unit_sale_price');

            if ($totalSale) {

                $totalSale = DB::table('owner_calculation')
                    ->where('product_code', $product->product_id)
                    ->pluck('total_sale');

                $totalSale = $totalSale + $product->product_quantity * $unit_price;


                DB::table('owner_calculation')
                    ->where('product_code', $product->product_id)
                    ->update(['total_sale' => $totalSale]);
            } else {

                DB::table('owner_calculation')
                    ->insert([
                        'product_code' => $product->product_id,
                        'total_sale' => $product->product_quantity * $unit_price
                    ]);
            }


            /** owner table insert end **/
        }


        return Redirect::to('stockPending');
    }
    //nazmul_vai end


    /** Daily stock view page. Shows only the products current stock situations which are sold today **/
    //nazmul vai start
//    public function dailyStock()
//    {
//        $i = 0;
//
//        $bills = Bill::where('bill_date', '>=', date('Y-m-d'))
//            ->where('stock_clear', 1)
//            ->lists('bill_id');
//
//
//        if (sizeof($bills) == 0)//No Product sold today
//        {
//            return View::make('stock.dailyStocks', compact('data'));
//        }
//        //eikhane system date kheal rakhte hobe. naile sob null asbe
//
//        $products = BillProduct::whereIn('bill_id', $bills)
//            ->distinct()
//            ->lists('product_code');
//
//        foreach ($products as $product) {
//            $data[$i]['product_code'] = $product;
//
//            $data[$i]['unit_product_size'] = Product::where('product_code', $product)->pluck('unit_product_size');
//
//            $total = Product::where('product_code', $product)->sum('quantity') - Product::where('product_code', $product)->sum('wastage_before_stock');
//
//            $sold_today = BillProduct::whereIn('bill_id', $bills)
//                ->where('product_code', $product, 1)
//                ->sum('total_piece');
//
//            $sold_till_now = ProductTracker::where('product_code', $product)->pluck('total_sold_unit') + ProductTracker::where('product_code', $product)->pluck('wastage_after_stock');
//
//            $data[$i]['opening_stock'] = $total - ($sold_till_now - $sold_today);
//
//            $x = explode('X', $data[$i]['unit_product_size']);
//
//            $size = ($x[0] * $x[1]) / 144;
//
//            $data[$i]['total_stock'] = $data[$i]['opening_stock'] * $size;
//
//            $data[$i]['total_sold_today'] = $sold_today;//piece
//
//            $data[$i]['total_sold_today_sft'] = $sold_today * $size;//sft
//
//            $data[$i]['pieces_available'] = $data[$i]['opening_stock'] - $sold_today;
//
//            $data[$i]['closing'] = $data[$i]['pieces_available'] * $size;
//
//            $i++;
//        }
//
//        return View::make('stock.dailyStocks', compact('data'));
//    }
//nazmul vai end

    public function dailyStock()
    {
        $i = 0;


        $bills = DB::table('chalan_parent')
            ->where('created_at', '>=', date('Y-m-d'))
            ->where('clear', 1)
            ->orderBy('id','desc')
            ->lists('id');


        if (sizeof($bills) == 0)//No Product sold today
        {
            return View::make('stock.dailyStocks', compact('data'));
        }
        //eikhane system date kheal rakhte hobe. naile sob null asbe

        $products = DB::table('chalan_product')
            ->whereIn('chalan_id', $bills)
            ->distinct()
            ->lists('product_id');

        foreach ($products as $product) {
            $data[$i]['product_code'] = $product;

            $data[$i]['unit_product_size'] = Product::where('product_code', $product)->pluck('unit_product_size');

            $total = Product::where('product_code', $product)->sum('quantity') - Product::where('product_code', $product)->sum('wastage_before_stock');

            $sold_today = DB::table('chalan_product')->whereIn('chalan_id', $bills)
                ->where('product_id', $product)
                ->sum('total_piece');

            if ($sold_today == 0) {
                unset($data[$i]);
                continue;
            }

            $sold_till_now = ProductTracker::where('product_code', $product)->pluck('total_sold_unit') + ProductTracker::where('product_code', $product)->pluck('wastage_after_stock');

            $data[$i]['opening_stock'] = $total - ($sold_till_now - $sold_today);

            $x = explode('X', $data[$i]['unit_product_size']);

            $size = ($x[0] * $x[1]) / 144;

            $data[$i]['total_stock'] = $data[$i]['opening_stock'] * $size;

            $data[$i]['total_sold_today'] = $sold_today;//piece

            $data[$i]['total_sold_today_sft'] = $sold_today * $size;//sft

            $data[$i]['pieces_available'] = $data[$i]['opening_stock'] - $sold_today;

            $data[$i]['closing'] = $data[$i]['pieces_available'] * $size;

            $i++;
        }

        return View::make('stock.dailyStocks', compact('data'));
    }

    public function update($id)
    {
        //
    }

    /** Wastage amound input form get Request handler **/
    public function wastage()
    {
        $products = ProductTracker::lists('product_code');

        return View::make('products.wastage', compact('products'));
    }

    /** Wastage amound input form post submit Request handler **/
    public function wastageStore()
    {
        $x = ProductTracker::where('product_code', Input::get('product'))->pluck('wastage_after_stock');

        ProductTracker::where('product_code', Input::get('product'))
            ->update(
                [
                    'wastage_after_stock' => (Input::get('amount') + $x)
                ]);

        return Redirect::route('products.index');
    }


}
