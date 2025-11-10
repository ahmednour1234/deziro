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
        Schema::create('gift_payments', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->decimal('amount', 10, 2); // Example: 10 digits, 2 decimal places
            $table->enum('payment_method', ['wish', 'omt', 'matensa']);
            $table->string('ltn_number')->nullable();
            $table->string('receipt')->nullable();
            $table->string('status');
            $table->text('reason')->nullable(); // Nullable field for a reason
            $table->timestamps(); // Created at and Updated at timestamps

            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_payments');
    }
};
