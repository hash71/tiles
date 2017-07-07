<?php

class ProductTracker extends \Eloquent {
	protected $fillable = [];
	protected $guarded = ['id'];

	protected $table = 'product_tracker';

	public $timestamps = false;
}