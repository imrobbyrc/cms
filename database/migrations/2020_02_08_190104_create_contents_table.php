<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->bigIncrements('idContents');
            $table->unsignedBigInteger('submenuId');
            $table->foreign('submenuId')->references('idSubmenus')->on('submenus')->onDelete('cascade');
            $table->string('contents');
            $table->text('title');
            $table->text('description');
            $table->string('link');
            $table->text('image');
            $table->enum('status', array('active', 'inactive'))->default('inactive');
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
        Schema::dropIfExists('contents');
    }
}
