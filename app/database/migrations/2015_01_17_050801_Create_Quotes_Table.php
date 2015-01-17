<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('quotes', function($table)
		{
			$table->increments('id');
			$table->integer('quote_channels_id');
			$table->string('text');
			$table->timestamps();
		});

		Schema::create('quote_channels', function($table)
		{
			$table->increments('id');
			$table->string('key');
			$table->string('name');
			$table->string('display_name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('quotes');
		Schema::drop('quote_channels');
	}

}
