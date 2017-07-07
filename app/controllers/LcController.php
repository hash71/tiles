<?php

class LcController extends \BaseController {

	/** Display a listing of lcs **/
	public function index()
	{
		$lcs = Lc::all(); //Get all LC numbers

		$i = 0;

		$product_list = null;

		foreach ($lcs as $variable)
		{
			$product_list[$i++] = Product::where('lc_number',$variable->lc_number)->get();
		}

		return View::make('lc.index',compact('lcs','product_list'));
	}


	/** Show the form for creating a new lc **/
	public function create()
	{
		return View::make('lcs.create');
	}


	/** Display the specified lc. **/
	public function show($id)
	{
		$lc = Lc::findOrFail($id);

		return View::make('lcs.show', compact('lc'));
	}


	/** Show the form for editing the specified lc. **/
	public function edit($id)
	{
		$lc = Lc::find($id);

		return View::make('lcs.edit', compact('lc'));
	}


	/** Update the specified lc in storage. **/
	public function update($id)
	{
		$lc = Lc::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Lc::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$lc->update($data);

		return Redirect::route('lcs.index');
	}


	/** Remove the specified lc from storage. **/
	public function destroy($id)
	{
		Lc::destroy($id);

		return Redirect::route('lcs.index');
	}


	/** Delete an speicific LC **/
	public function lcDelete($id)
	{
		//Delete from LC info table
		DB::table('lc_info')
			->where('lc_number',$id)
			->delete();

		//Find all the products Entered in this LC
		$products = DB::table('lc_product')
						->where('lc_number',$id)
						->lists('product_code');

		//Delete this LC from LC Product table
		DB::table('lc_product')
			->where('lc_number',$id)
			->delete();

		//Delete all the products Entered in this LC
		foreach ($products as $product)
		{
			$found = DB::table('lc_product')
						->where('product_code',$product)
						->get();

			if(!sizeof($found))
			{
				DB::table('product_tracker')
					->where('product_code',$product)
					->delete();
			}
		}

		return Redirect::route('lc.index');
	}

}
