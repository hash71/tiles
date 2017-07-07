<?php

class Product extends \Eloquent {


	public $timestamps = false;
	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	protected $guarded = ['id'];

	protected $table = 'lc_product';

}