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
        Schema::table('specials', function (Blueprint $table) {
            $table->string('title')->nullable()->after('name');
            $table->string('title_kh')->nullable()->after('title');
            $table->text('description')->nullable()->after('image');
            $table->text('description_kh')->nullable()->after('description');
            $table->integer('discount_percentage')->default(0)->after('description_kh');
            $table->string('discount_type')->default('percentage')->after('discount_percentage'); // percentage, fixed
            $table->timestamp('valid_from')->nullable()->after('discount_type');
            $table->timestamp('valid_until')->nullable()->after('valid_from');
            $table->boolean('is_active')->default(true)->after('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specials', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'title_kh',
                'description',
                'description_kh',
                'discount_percentage',
                'discount_type',
                'valid_from',
                'valid_until',
                'is_active',
            ]);
        });
    }
};
