<?php

class ProductsController extends \BaseController
{

	public static $xy = 1;


	/** Product list View page **/
	public function index()
	{
		$selected = -1;
		$grp 		= 0;

		$units_size = Product::select('unit_product_size')
													->distinct()
													->orderBy('unit_product_size')
													->get();

		$products = ProductTracker::lists('product_code');


		if(sizeof($products)==0) //No products to show
		{
			return View::make('products.index', compact('data','units_size','selected','grp'));
		}


		$i = 1;

		//Get all info for all the products
		foreach ($products as $product)
		{
			$data[$i]['product_code'] 	= $product;

			$data[$i]['lc_number'] 		= Product::where('product_code',$product)->lists('lc_number');

			$data[$i]['unit_size'] 		= Product::where('product_code',$product)->lists('unit_product_size');

			$data[$i]['unit_cost'] 		= Product::where('product_code',$product)->lists('unit_product_cost');

			$data[$i]['total_in'] 		= Product::where('product_code',$product)->sum('quantity');

			$data[$i]['w_b_s'] 			= Product::where('product_code',$product)->sum('wastage_before_stock');

			$data[$i]['w_a_s'] 			= ProductTracker::where('product_code',$product)->pluck('wastage_after_stock');

			$data[$i]['ttl_sld'] 		= ProductTracker::where('product_code',$product)->pluck('total_sold_unit');

			$total 	= Product::where('product_code',$product)->sum('quantity')-Product::where('product_code',$product)->sum('wastage_before_stock');

			$sold_till_now 	= ProductTracker::where('product_code',$product)->pluck('total_sold_unit') + ProductTracker::where('product_code',$product)->pluck('wastage_after_stock');

			$data[$i]['current_stock'] 	= $total - $sold_till_now;

			$i++;
		}

		return View::make('products.index', compact('data','units_size','selected','grp'));
	}

	public function create()
	{
		return View::make('products.create');
	}


	/** Insert new product through with LC info **/
	public function store()
	{
		$lc = new Lc;

		$lc->lc_number 	= Input::get('lc_number');
		$lc->lc_date 	= Input::get('lc_date');
		$lc->save();

		$pc = Input::get('product_code1');
		$i 	= 1;

		while($pc)
		{
			$product = new Product;

			$product->lc_number 			= Input::get('lc_number');
			$product->product_code 			= Input::get('product_code'.$i);
			$product->quantity 				= Input::get('quantity'.$i);
			$product->unit_product_size 	= Input::get('first_unit_product_size'.$i)."X".Input::get('second_unit_product_size'.$i);

			$product->wastage_before_stock 	= Input::get('wastage_before_stock'.$i);

			$product->save();

			/* Insert into product tracker */
			$code = ProductTracker::where('product_code',Input::get('product_code'.$i))->pluck('product_code');

			if(empty($code) || is_null($code))
			{
				$product_tracker 				= new ProductTracker;
				$product_tracker->product_code 	= Input::get('product_code'.$i);
				$product_tracker->box 			= Input::get('perbox'.$i);
				$product_tracker->save();
			}
			/*end of insertion inproduct tracker*/

			$i++;

			$pc = Input::get('product_code'.$i);
		}

		return Redirect::to('products/create')->with('success', 'Products entry successful.');
	}


	/** Get result after Filtering in all product list page **/
	public function filtering()
	{
		$selected 	= Input::get('unit_size');
		$grp 		= Input::get('group_by');
		$units_size = Product::select('unit_product_size')
						->distinct()
						->orderBy('unit_product_size')
						->get();

		$products = ProductTracker::lists('product_code');

		if(sizeof($products)==0) //No products to show
		{
			return View::make('products.index', compact('data','units_size','selected','grp'));
		}

		$i = 1;

		foreach ($products as $product)
		{
			$usize = Product::where('product_code',$product)->pluck('unit_product_size');

			if($usize != $selected && $selected !=-1)
				continue;

			$data[$i]['product_code'] 	= $product;

			$data[$i]['lc_number'] 		= Product::where('product_code',$product)->lists('lc_number');

			$data[$i]['unit_size'] 		= Product::where('product_code',$product)->lists('unit_product_size');

			$data[$i]['unit_cost'] 		= Product::where('product_code',$product)->lists('unit_product_cost');

			$data[$i]['total_in'] 		= Product::where('product_code',$product)->sum('quantity');

			$data[$i]['w_b_s'] 			= Product::where('product_code',$product)->sum('wastage_before_stock');

			$data[$i]['w_a_s'] 			= ProductTracker::where('product_code',$product)->pluck('wastage_after_stock');

			$data[$i]['ttl_sld'] 		= ProductTracker::where('product_code',$product)->pluck('total_sold_unit');

			$total = Product::where('product_code',$product)->sum('quantity')-Product::where('product_code',$product)->sum('wastage_before_stock');

			$sold_till_now = ProductTracker::where('product_code',$product)->pluck('total_sold_unit') + ProductTracker::where('product_code',$product)->pluck('wastage_after_stock');

			$data[$i]['current_stock'] = $total - $sold_till_now;

			$i++;
		}

		/* If group option selected, Sum up the products in group */
		if($grp)
		{
			$j=$i;

			for ($i=1; $i <$j ; $i++)
			{
				$x = explode('-', $data[$i]['product_code']);

				for($k=$i+1; $k<$j; $k++)
				{
					$y = explode('-', $data[$k]['product_code']);

					if($y[0] == $x[0])
					{
						foreach ($data[$k]['lc_number'] as $p)
						{
							array_push($data[$i]['lc_number'],$p);
						}

						$data[$i]['lc_number'] 		= array_unique($data[$i]['lc_number']);

						$data[$i]['total_in']		+= $data[$k]['total_in'];

						$data[$i]['w_b_s']			+= $data[$k]['w_b_s'];

						$data[$i]['w_a_s']			+= $data[$k]['w_a_s'];

						$data[$i]['ttl_sld']		+= $data[$k]['ttl_sld'];

						$data[$i]['current_stock']	+= $data[$k]['current_stock'];

						$data[$k]['product_code'] 	= null;
					}
				}
			}

			foreach ($data as $key => $value)
			{
				if( is_null(($value['product_code'] )))
				{
					unset($data[$key]);
				}
				else
				{
					$z = explode('-', $value['product_code']);
					$data[$key]['product_code'] = $z[0];
				}
			}
			/* Grouping products ends */
		}

		return View::make('products.index', compact('data','units_size','selected','grp'));
	}

}
