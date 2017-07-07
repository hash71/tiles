<?php

class Client extends \Eloquent {

	public $timestamps = false;

	protected $table = 'client';

	protected $primaryKey = 'client_id';

	// Add your validation rules here
	public static $rules = [
		'client_name' => 'required',
		'mobile_number' => 'required',
		'client_email' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	protected $guarded = ['client_id'];

}