<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Onboarding;
use Illuminate\Support\Facades\DB;

class OnboardingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing onboarding data
        DB::table('onboardings')->delete();

        // Create onboarding screens
        Onboarding::create([
            'title' => 'ភូមិនំបញ្ចុក',
            'title_en' => 'Phum Num Banh Chok',
            'description' => 'សូមស្វាគមន៍មកកាន់ភូមិនំបញ្ចុក! តាក់តែងនំបញ្ចុកថ្មីសម្រាប់អ្នកគ្រប់ថ្ងៃ។ អាហារខ្មែរប្រពៃណីដែលមានរសជាតិឆ្ងាញ់។ យើងនាំអោយអ្នកបានកសាងសង្គមដោយបច្ចេកវិទ្យាទំនើប',
            'description_en' => 'Welcome to Phum Num Banh Chok! Fresh traditional Cambodian noodles every day. Authentic Khmer cuisine with delicious taste.',
            'image' => 'storage/onboarding/onboard1_img1.jpg',
            'image_secondary' => 'storage/onboarding/onboard1_img2.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        Onboarding::create([
            'title' => 'ចូលរក់សម្រាប់អ្នក',
            'title_en' => 'Order for You',
            'description' => 'រកមើលម្ហូបដែលអ្នកចូលចិត្ត ហើយបញ្ជាទិញតាមរយៈកម្មវិធីដោយងាយស្រួល។ ការដឹកជញ្ជូនលឿន និងទុកចិត្តបាន។',
            'description_en' => 'Find your favorite dishes and order easily through our app. Fast and reliable delivery.',
            'image' => 'storage/onboarding/onboard2_img1.jpg',
            'image_secondary' => null,
            'order' => 2,
            'is_active' => true,
        ]);

        Onboarding::create([
            'title' => 'ដឹកជញ្ជូនលឿន',
            'title_en' => 'Fast Delivery',
            'description' => 'យើងធានាថាអាហាររបស់អ្នកនឹងមកដល់ក្នុងពេលខ្លីបំផុត។ ស្រស់ និងក្តៅៗពីផ្ទះបាយ។',
            'description_en' => 'We ensure your food arrives in the shortest time. Fresh and hot from the kitchen.',
            'image' => 'storage/onboarding/onboard3_img1.jpg',
            'image_secondary' => null,
            'order' => 3,
            'is_active' => true,
        ]);
    }
}
