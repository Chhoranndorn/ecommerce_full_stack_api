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
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_picture')->nullable()->after('email');
            $table->string('whatsapp')->nullable()->after('phone');
            $table->string('telegram')->nullable()->after('whatsapp');
            $table->string('wechat')->nullable()->after('telegram');
            $table->text('address')->nullable()->after('wechat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_picture', 'whatsapp', 'telegram', 'wechat', 'address']);
        });
    }
};
