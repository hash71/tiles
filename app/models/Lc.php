<?php

class Lc extends \Eloquent {
	
	public $timestamps = false;
	
	protected $fillable = [];

	protected $guarded = ['id'];

	protected $table = 'lc_info';



}