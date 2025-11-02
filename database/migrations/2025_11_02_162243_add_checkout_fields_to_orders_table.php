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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('total');
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('subtotal');
            $table->string('payment_method')->nullable()->after('delivery_fee');
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->text('notes')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'delivery_fee', 'payment_method', 'payment_status', 'notes']);
        });
    }
};
