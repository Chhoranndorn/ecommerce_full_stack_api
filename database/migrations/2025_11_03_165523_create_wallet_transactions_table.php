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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['credit', 'debit']); // credit = money added, debit = money spent
            $table->decimal('amount', 10, 2);
            $table->decimal('balance_after', 10, 2); // balance after transaction
            $table->string('description'); // e.g., "Product Order", "Point Conversion", etc.
            $table->string('reference_type')->nullable(); // e.g., "Order", "PointConversion"
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of related record
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
