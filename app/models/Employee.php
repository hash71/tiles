<?php

class Employee extends \Eloquent {
	public $timestamps = false;

	protected $table = 'hr';

	// Add your validation rules here
	// public static $rules = [
	// 	// 'title' => 'required'
	// 	'name'=>'required',
	// 	'mobile_number'=>'required',
	// 	'address'=>'required',
	// 	'designation'=>'required',
	// 	'started_working_on'=>'required',
	// 	'salary'=>'required',
	// 	'email'=>'required'
	// ];

	// Don't forget to fill this array
	protected $fillable = [];

	protected $guarded = ['id'];
}