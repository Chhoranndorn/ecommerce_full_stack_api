<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing languages
        DB::table('languages')->delete();

        // Create languages based on the language selection screen
        Language::create([
            'name' => 'ខ្មែរ',
            'name_en' => 'Khmer',
            'code' => 'km',
            'flag_icon' => 'storage/flags/cambodia.png',
            'is_default' => true,
            'is_active' => true,
            'order' => 1,
        ]);

        Language::create([
            'name' => 'អង់គ្លេស',
            'name_en' => 'English',
            'code' => 'en',
            'flag_icon' => 'storage/flags/uk.png',
            'is_default' => false,
            'is_active' => true,
            'order' => 2,
        ]);
    }
}
