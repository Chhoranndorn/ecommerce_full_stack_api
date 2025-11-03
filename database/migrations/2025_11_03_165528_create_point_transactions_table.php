<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['earned', 'withdrawn', 'expired']); // earned from orders, withdrawn for conversion, expired
            $table->integer('points'); // positive for earned, negative for withdrawn
            $table->integer('balance_after'); // points balance after transaction
            $table->string('description'); // e.g., "Purchase Reward", "Converted to Money"
            $table->string('reference_type')->nullable(); // e.g., "Order", "Conversion"
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of related record
            $table->timestamps();

            $table->index(['user_id', 'type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
