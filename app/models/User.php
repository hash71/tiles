<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	public $timestamps = false;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');


	protected $fillable = array('id','user_name','mail','password','role_id');




	//disabling remember token

	public function getRememberToken()
	{
	    // return $this->remember_token;
	    return null;
	}

	public function setRememberToken($value)
	{
	    // $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    // return 'remember_token';
	    return null;
	}

	public function setAttribute($key, $value){
		return null;
	}

}
