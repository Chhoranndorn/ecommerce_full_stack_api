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
            'app_name_kh' => 'ភូមិនំបញ្ចុក',
            'tagline' => 'Authentic Cambodian Cuisine',
            'logo' => 'storage/logo/app_logo.png',
            'splash_logo' => 'storage/logo/splash_logo.png',
            'splash_background_color' => '#7CB342', // Green color from splash
            'primary_color' => '#7CB342',
            'phone' => '+855 12 487 992',
            'email' => 'phumnumbanhchok@gmail.com',
            'address' => 'សុីមរៀប Phum Num Banh Chok Siem Reap, Cambodia 171201',
            'about_us' => 'Welcome to Phum Num Banh Chok! We are dedicated to bringing you the most authentic Cambodian cuisine experience. Our specialty is traditional Num Banh Chok (Khmer noodles) made fresh daily using time-honored recipes passed down through generations. Experience the true taste of Cambodia with us.',
            'about_us_kh' => 'សូមស្វាគមន៍មកកាន់ភូមិនំបញ្ចុក! យើងខ្ញុំប្តេជ្ញាចិត្តនាំយកបទពិសោធន៍ម្ហូបខ្មែរដ៏ពិតប្រាកដបំផុតមកជូនអ្នក។ ជំនាញពិសេសរបស់យើងគឺនំបញ្ចុកខ្មែរប្រពៃណីដែលធ្វើស្រស់ប្រចាំថ្ងៃដោយប្រើរូបមន្តបុរាណដែលបានបញ្ជូនតាមជំនាន់។ សូមទទួលបទពិសោធន៍រសជាតិពិតប្រាកដរបស់កម្ពុជាជាមួយយើង។',
            'banner_image' => null,
            'facebook' => null,
            'instagram' => null,
            'telegram' => null,
            'website' => null,
            'latitude' => null,
            'longitude' => null,
            'currency' => 'USD',
            'currency_symbol' => '$',
            'delivery_fee' => 1.00,
            'tax_rate' => 0.00,
            'app_version' => '1.0.0',
            'is_maintenance' => false,
        ]);
    }
}
