<?php

class UsersController extends \BaseController {

	/** Display a listing of users **/
	public function index()
	{
		$users 	= User::all();
		$houses = DB::table('house')->get();

		return View::make('users.index', compact('users','houses'));
	}


	/** Show the form for creating a new user **/
	public function create()
	{
		$houses = DB::table('house')->get();

		return View::make('users.create',compact('houses'));
	}


	/** Store a newly created user in storage **/
	public function store()
	{
		DB::table('user')
			->insert(
				[
					'user_name'	=>Input::get('user_name'),
					'mail'		=>Input::get('mail'),
					'password'	=>Hash::make(Input::get('password')),
					'role'		=>Input::get('role'),
					'house_id'	=>Input::get('house_id')
				]);

		return Redirect::route('users.index');
	}


	/** Display the specified user **/
	public function show($id)
	{
		$user = User::findOrFail($id);

		return View::make('users.show', compact('user'));
	}


	/** Show the form for editing the specified user **/
	public function edit($id)
	{
		$user 	= User::find($id);
		$houses = DB::table('house')->get();

		return View::make('users.edit', compact('user','houses'));
	}


	/** Update the specified user in storage **/
	public function update($id)
	{

		if(!Input::get('password')) //If password won't changed
		{

			DB::table('user')
				->where('id',$id)
				->update(
					[
						'user_name'	=>Input::get('user_name'),
						'mail'		=>Input::get('mail'),
						'role'		=>Input::get('role'),
						'house_id'	=>Input::get('house_id')
					]);
		}
		else //password will be changed too
		{
			DB::table('user')
				->where('id',$id)
			    ->update(
			    	[
			    		'user_name'	=>Input::get('user_name'),
						'mail'		=>Input::get('mail'),
						'password'	=>Hash::make(Input::get('password')),
						'role'		=>Input::get('role'),
						'house_id'	=>Input::get('house_id')
					]);
		}

		return Redirect::route('users.index');
	}

	/** Remove the specified user from storage **/
	public function destroy($id)
	{
		User::destroy($id);

		return Redirect::route('users.index');
	}

}
