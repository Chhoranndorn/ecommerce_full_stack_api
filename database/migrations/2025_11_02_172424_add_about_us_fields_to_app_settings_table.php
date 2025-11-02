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
        Schema::table('app_settings', function (Blueprint $table) {
            $table->text('about_us')->nullable()->after('address');
            $table->text('about_us_kh')->nullable()->after('about_us');
            $table->string('banner_image')->nullable()->after('about_us_kh');
            $table->string('facebook')->nullable()->after('banner_image');
            $table->string('instagram')->nullable()->after('facebook');
            $table->string('telegram')->nullable()->after('instagram');
            $table->string('website')->nullable()->after('telegram');
            $table->decimal('latitude', 10, 8)->nullable()->after('website');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn([
                'about_us',
                'about_us_kh',
                'banner_image',
                'facebook',
                'instagram',
                'telegram',
                'website',
                'latitude',
                'longitude',
            ]);
        });
    }
};
