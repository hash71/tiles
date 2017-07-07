<?php

class SessionsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /sessions
	 *
	 * @return Response
	 */
	public function index()
	{
		return Redirect::to('/');
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /sessions/create
	 *
	 * @return Response
	 */
	/* if the user hit login page then this page will be shown with the form. from this page's form that will redirected to store */
	public function create()
	{
		if (Auth::check()){
			return Redirect::to('/index');
		}
		return View::make('sessions.create');//sessions.create = view to be created
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /sessions
	 *
	 * @return Response
	 */
	public function store()
	{
		if (Auth::attempt(Input::only('user_name','password')))
		{
			return Redirect::to('/index');
		}
		else
		{
			return Redirect::to('login')->withInput();
		}
	}

	/**
	 * Display the specified resource.
	 * GET /sessions/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return Redirect::to('/');
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /sessions/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return Redirect::to('/');
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /sessions/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return Redirect::to('/');
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /sessions/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		Auth::logout();

		return Redirect::to('login');
	}

}