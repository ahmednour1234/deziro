<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('phone')->unique();
            $table->string('store_name')->nullable();
            $table->string('position')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('certificate')->nullable();
            $table->string('categories')->nullable();
            $table->string('status')->nullable();
            $table->boolean('type');
            $table->boolean('is_active')->nullable();
            $table->string('reason')->nullable();
            $table->string('fcm_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('users');
    }
};
