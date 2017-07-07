<?php

class Bill extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	protected $table = 'bill_info';

	public $timestamps = false;

	protected $primaryKey = 'bill_id';

	protected $guarded = ['bill_date'];

}