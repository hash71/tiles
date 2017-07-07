<?php

class EmployeesController extends \BaseController {

	/** Display a listing of employees **/
	public function index()
	{
		$employees = Employee::all();

		return View::make('employees.index', compact('employees'));
	}

	/** Show the form for creating a new employee **/
	public function create()
	{
		return View::make('employees.create');
	}

	/** Store a newly created employee in storage. **/
	public function store()
	{
		$image = Input::file('file');

		if($image != null)
		{
	        $filename  	= time() . '.' . $image->getClientOriginalExtension();
	        $path 		= public_path('img/' . $filename);

	        Image::make($image->getRealPath())->resize(72, 72)->save($path);
		}
		else
		{
			$filename = 'placeholder.jpg';
		}

		$employee = new Employee;

		$employee->name 				= Input::get('name');
		$employee->image 				= $filename;
		$employee->mobile_number 		= Input::get('mobile_number');
		$employee->address 				= Input::get('address');
		$employee->designation 			= Input::get('designation');
		$employee->started_working_on 	= Input::get('started_working_on');
		$employee->salary 				= Input::get('salary');
		$employee->email 				= Input::get('email');

		$employee->save();

		return Redirect::route('employees.index');
	}

	/** Display the specified employee by ID **/
	public function show($id)
	{
		$employee = Employee::where('id',$id)->first();

		$payments = DB::table('expense')
						->where('category','Employee_Payment')
						->where('description',$id)
						->orderBy('date','desc')
						->get();

		return View::make('employees.show', compact('employee','payments'));
	}

	/** Show the form for editing the specified employee. **/
	public function edit($id)
	{
		$employee = Employee::find($id);

		return View::make('employees.edit', compact('employee'));
	}

	/** Update the specified employee in storage. **/
	public function update($id)
	{
		$employee = Employee::findOrFail($id);

		$employee->update($data = Input::all());

		return Redirect::route('employees.index');
	}

	/** Remove the specified employee from storage. **/
	public function destroy($id)
	{
		Employee::destroy($id);

		return Redirect::route('employees.index');
	}

	/** Client payment form submit handler **/
	public function payment()
	{
		DB::table('expense')
			->insert(
				[
					'category'=>'Employee_Payment',
					'description'=>Input::get('employee'),
					'amount'=>Input::get('amount'),
					'house_id'=>Auth::user()->house_id
				]);

		return Redirect::to('employee_payment');
	}

}
