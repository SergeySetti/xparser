<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class XparserCreateUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('xparser_urls', function(Blueprint $table){
            $table->increments('id');
            $table->bigInteger('site_key');
            $table->string('url');
            $table->boolean('processed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::drop('xparser_urls');
    }
}
