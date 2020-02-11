<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submenus', function (Blueprint $table) {
            $table->bigIncrements('idSubmenus');
            $table->unsignedBigInteger('menuId');
            $table->foreign('menuId')->references('idMenus')->on('menus')->onDelete('cascade');
            $table->string('submenus');
            $table->text('title');
            $table->text('description');
            $table->string('link');
            $table->text('image');
            $table->enum('status', array('active', 'inactive'))->default('inactive');
            $table->enum('layout', array('1', '2'))->default('1');
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
        Schema::dropIfExists('submenus');
    }
}
