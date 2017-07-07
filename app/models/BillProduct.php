<?php

class BillProduct extends \Eloquent {

	protected $guarded = ['id'];

	protected $fillable = [];

	public $timestamps = false;

	protected $table = 'bill_product';


}