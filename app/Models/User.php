<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	protected $table = 'users';

	protected $guarded = ['id'];

	protected $dates = ['fbtoken_expires'];

	protected $casts = [
		'is_active' => 'boolean'
	];
}
