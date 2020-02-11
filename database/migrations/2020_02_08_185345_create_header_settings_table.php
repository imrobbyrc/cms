<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeaderSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('header_settings', function (Blueprint $table) {
            $table->bigIncrements('idHeader');
            $table->string('browserIcon')->default('logo-placeholder.png');
            $table->string('headerLogo')->default('logo-placeholder.png');
            $table->text('contentLeft');
            $table->text('contentRight');
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
        Schema::dropIfExists('header_settings');
    }
}
