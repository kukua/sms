<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('content', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('type');
			$table->string('city');
			$table->double('lat', 9, 6);
			$table->double('lng', 9, 6);
			$table->string('content');
			$table->timestamps();
		});

		Schema::table('clients', function(Blueprint $table) {
			$table->dropColumn('type');
			$table->dropColumn('lat');
			$table->dropColumn('lng');
			$table->dropColumn('city');
			$table->integer('content_id')->after('id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::drop('content');
		Schema::table('clients', function(Blueprint $table) {
			$table->dropColumn('content_id');
			$table->integer('type')->after('id');
		});
    }
}
