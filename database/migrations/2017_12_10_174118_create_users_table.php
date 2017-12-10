<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('email');
			$table->string('fb_token')->unique();
			$table->string('fb_uid')->unique();
			$table->string('picture');
			$table->string('link');
			$table->boolean('is_active')->default(true);
			$table->rememberToken();
			$table->timestamp('fb_token_expires');
			$table->timestamps();

			$table->index('fb_uid');
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
