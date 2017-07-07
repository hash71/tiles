<?php

class ClientsController extends \BaseController {

	/** Display a listing of clients **/
	public function index()
	{
		$clients = Client::all();
		return View::make('clients.index')->with('clients',$clients);
	}

	/** Show the form for creating a new client **/
	public function create()
	{
		return View::make('clients.create');
	}

	/** Store a newly created client in storage **/
	public function store()
	{
		Client::create($data = Input::all());

		return Redirect::route('clients.index');
	}

	/** Display the specified client by ID **/
	public function show($id)
	{
		$client = Client::where('client_id',$id)->first();

		return View::make('clients.show', compact('client'));
	}

	/** Show the form for editing the specified client. **/
	public function edit($id)
	{
		$client = Client::find($id);

		return View::make('clients.edit', compact('client'));
	}

	/** Update the specified client in storage. **/
	public function update($id)
	{

		$client = Client::where('client_id',$id)->first();

		$validator = Validator::make($data = Input::all(), Client::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$client->update($data);

		return Redirect::route('clients.index');
	}

	/** Remove the specified client from storage. **/
	public function destroy($id)
	{
		Client::destroy($id);

		return Redirect::route('clients.index');
	}

}
