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
        Schema::table('addresses', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->string('type')->default('home')->after('user_id'); // home, office, other
            $table->text('address')->after('type');
            $table->string('name')->after('address');
            $table->string('phone')->after('name');
            $table->decimal('latitude', 10, 8)->nullable()->after('phone');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->boolean('is_default')->default(false)->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'type', 'address', 'name', 'phone', 'latitude', 'longitude', 'is_default']);
        });
    }
};
