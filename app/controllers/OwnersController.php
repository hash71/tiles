<?php

class OwnersController extends \BaseController {

	/** Index page of the owner User **/
	public function index()
	{
		//Get all the products sale info from product tracker table
		$products = ProductTracker::lists('product_code');

		if(sizeof($products) == 0)
		{
			return View::make('owners.index', compact('data'));
		}

		$i = 1;

		foreach ($products as $product)
		{
			$data[$i]['product_code'] 	= $product;

			$data[$i]['lc_number'] 		= Product::where('product_code',$product)
											->lists('lc_number');

			if(sizeof($data[$i]['lc_number']))
			{
				$data[$i]['lc_date'] = 	Lc::whereIn('lc_number',$data[$i]['lc_number'])
											->orderBy('lc_number')
											->lists('lc_date');
			}
			else
			{
				$data[$i]['lc_date'] = null;
			}


			$data[$i]['unit_size'] = Product::where('product_code',$product)->pluck('unit_product_size');

			$data[$i]['unit_cost'] = Product::where('product_code',$product)->lists('unit_product_cost');

			$total = Product::where('product_code',$product)->sum('quantity')-Product::where('product_code',$product)->sum('wastage_before_stock');

			$sold_till_now = ProductTracker::where('product_code',$product)->pluck('total_sold_unit')+ProductTracker::where('product_code',$product)->pluck('wastage_after_stock');

			$data[$i]['current_stock'] 	= $total - $sold_till_now;

			$data[$i]['total'] 			= $total;

			$data[$i]['sold_till_now'] 	= $sold_till_now;

			$data[$i]['buy_rate'] 		= DB::table('owner_calculation')
											->where('product_code',$product)
											->pluck('buy_rate');

			if(is_null($data[$i]['buy_rate']))
			{
				$data[$i]['buy_rate'] = 0.00;
			}

			$data[$i]['total_sale'] = DB::table('owner_calculation')
										->where('product_code',$product)
										->pluck('total_sale');

			if(is_null($data[$i]['total_sale']))
			{
				$data[$i]['tota_sale'] = 0.00;
			}

			$i++;
		}

		return View::make('owners.index', compact('data'));
	}


	/** Save the changes owner made in the main owner table in owner index page **/
	public function store()
	{
		$x = 1;

		//Get input from all the products field and insert/update it in DB
		while(Input::get('code'.$x))
		{
			if(DB::table('owner_calculation')->where('product_code',Input::get('code'.$x))->get()) //update
			{
				DB::table('owner_calculation')
					->where('product_code',Input::get('code'.$x))
					->update(['buy_rate'=>Input::get('buy'.$x)]);
			}
			else //insert
			{
				DB::table('owner_calculation')
					->insert(
						[
							'product_code'=>Input::get('code'.$x),
							'buy_rate'=>Input::get('buy'.$x),
							'total_sale'=>0
						]);
			}

			$x++;
		}

		return Redirect::route('owner.index');
	}


	/**Display a single products info totally **/
	public function show($id)
	{
		//Get all the bills where this product($id) was sold
		$bills 	= BillProduct::where('product_code',$id)->lists('bill_id');

		$info 	= null;

		$x = 0;

		foreach ($bills as $bill)
		{
			$info[$x]['bill_id'] 	= Bill::where('bill_id',$bill)->pluck('bill_id');

			$info[$x]['bill_date'] 	= Bill::where('bill_id',$bill)->pluck('bill_date');

			$salesman_id 			= Bill::where('bill_id',$bill)->pluck('salesman_id');

			$house_id 				= DB::table('user')
										->where('id',$salesman_id)
										->pluck('house_id');

			$info[$x]['house_name'] = DB::table('house')
										->where('house_id',$house_id)
										->pluck('house_name');

			$info[$x]['quantity'] 	= BillProduct::where('bill_id',$bill)
										->where('product_code',$id)
										->pluck('product_quantity');

			$info[$x]['unit_sale_price'] 	= BillProduct::where('bill_id',$bill)
												->where('product_code',$id)
												->pluck('unit_sale_price');

			$info[$x]['total_sale_price'] 	= $info[$x]['quantity'] * $info[$x]['unit_sale_price'];

			$x++;
		}

		return View::make('owners.show', compact('info','id'));
	}

}
