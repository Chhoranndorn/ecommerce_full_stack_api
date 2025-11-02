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
            $table->foreignId('delivery_method_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            $table->foreignId('coupon_id')->nullable()->after('delivery_method_id')->constrained()->onDelete('set null');
            $table->string('coupon_code')->nullable()->after('coupon_id');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('coupon_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_method_id']);
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['delivery_method_id', 'coupon_id', 'coupon_code', 'discount_amount']);
        });
    }
};
