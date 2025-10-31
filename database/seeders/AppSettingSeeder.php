<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppSetting;
use Illuminate\Support\Facades\DB;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing settings
        DB::table('app_settings')->delete();

        // Create app settings based on the splash screen
        AppSetting::create([
            'app_name' => 'Phum Num Banh Chok',
            'app_name_kh' => 'ភូមិ នំបញ្ចុក',
            'tagline' => 'Authentic Cambodian Cuisine',
            'logo' => 'storage/logo/app_logo.png',
            'splash_logo' => 'storage/logo/splash_logo.png',
            'splash_background_color' => '#7CB342', // Green color from splash
            'primary_color' => '#7CB342',
            'phone' => '+855 12 345 678',
            'email' => 'info@phumnumbanhchok.com',
            'address' => 'Phnom Penh, Cambodia',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'delivery_fee' => 2.00,
            'tax_rate' => 0.00,
            'app_version' => '1.0.0',
            'is_maintenance' => false,
        ]);
    }
}
