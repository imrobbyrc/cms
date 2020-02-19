<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('idMenus');
            $table->string('menu');
            $table->string('link');
            $table->text('image');
            $table->enum('status', array('active', 'inactive'))->default('inactive');
            $table->enum('layout', array('1', '2', '3'))->default('1');
            $table->enum('showOnHomepage', array('yes', 'no'))->default('no');
            $table->integer('priority')->unique();
            $table->string('browserTitle'); 
            $table->text('metaDescription');
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
        Schema::dropIfExists('menus');
    }
}
