<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('idContacts'); 
            $table->text('fullAddress'); 
            $table->text('contact'); 
            $table->text('serviceDescription1')->nullable();
            $table->string('serviceTittle1')->nullable();
            $table->text('serviceDescription2')->nullable();
            $table->string('serviceTittle2')->nullable();
            $table->text('serviceDescription3')->nullable();
            $table->string('serviceTittle3')->nullable();
            $table->enum('showOnHomepage', array('yes', 'no'))->default('no');
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
        Schema::dropIfExists('contacts');
    }
}
