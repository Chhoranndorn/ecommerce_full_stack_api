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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->string('app_name_kh')->nullable();
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();
            $table->string('splash_logo')->nullable();
            $table->string('splash_background_color')->default('#7CB342');
            $table->string('primary_color')->default('#7CB342');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('currency')->default('USD');
            $table->string('currency_symbol')->default('$');
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->string('app_version')->default('1.0.0');
            $table->boolean('is_maintenance')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
