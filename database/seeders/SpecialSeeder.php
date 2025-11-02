<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Special;
use Carbon\Carbon;

class SpecialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specials = [
            [
                'name' => 'Delicious Recipe In Town',
                'title' => 'Delicious Recipe In Town',
                'title_kh' => 'រូបមន្តឆ្ងាញ់ប្រណិតក្នុងក្រុង',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800',
                'description' => 'Get 50% off on all our delicious recipes. Try our fresh vegetables and authentic Khmer cuisine. Limited time offer!',
                'description_kh' => 'ទទួលបានការបញ្ចុះតម្លៃ 50% លើរូបមន្តឆ្ងាញ់ទាំងអស់របស់យើង។ សាកល្បងបន្លែស្រស់ និងម្ហូបខ្មែរពិតប្រាកដ។ ការផ្តល់ជូនពេលកំណត់!',
                'discount_percentage' => 50,
                'discount_type' => 'percentage',
                'valid_from' => Carbon::parse('2025-01-18'),
                'valid_until' => Carbon::parse('2025-01-30'),
                'is_active' => true,
            ],
            [
                'name' => 'Weekend Special',
                'title' => 'Weekend Special Discount',
                'title_kh' => 'បញ្ចុះតម្លៃពិសេសចុងសប្តាហ៍',
                'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800',
                'description' => 'Enjoy 30% off on all weekend orders. Perfect for family gatherings and celebrations.',
                'description_kh' => 'រីករាយជាមួយការបញ្ចុះតម្លៃ 30% សម្រាប់ការបញ្ជាទិញចុងសប្តាហ៍ទាំងអស់។ ល្អឥតខ្ចោះសម្រាប់ការជួបជុំគ្រួសារ និងការអបអរសាទរ។',
                'discount_percentage' => 30,
                'discount_type' => 'percentage',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'name' => 'First Order Promotion',
                'title' => 'First Order Get $10 OFF',
                'title_kh' => 'ការបញ្ជាទិញដំបូងទទួលបាន $10',
                'image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=800',
                'description' => 'New customers get $10 off on their first order. Start your culinary journey with us today!',
                'description_kh' => 'អតិថិជនថ្មីទទួលបានការបញ្ចុះតម្លៃ $10 លើការបញ្ជាទិញដំបូងរបស់ពួកគេ។ ចាប់ផ្តើមដំណើរការធ្វើម្ហូបរបស់អ្នកជាមួយយើងថ្ងៃនេះ!',
                'discount_percentage' => 10,
                'discount_type' => 'fixed',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'name' => 'Flash Sale',
                'title' => 'Flash Sale - 40% OFF',
                'title_kh' => 'ការលក់រហ័ស - 40%',
                'image' => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=800',
                'description' => 'Limited time flash sale! Get 40% off on selected items. Hurry while stocks last!',
                'description_kh' => 'ការលក់រហ័សពេលកំណត់! ទទួលបានការបញ្ចុះតម្លៃ 40% លើទំនិញជ្រើសរើស។ ប្រញាប់ពេលដែលស្តុកនៅសល់!',
                'discount_percentage' => 40,
                'discount_type' => 'percentage',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addDays(7),
                'is_active' => true,
            ],
        ];

        foreach ($specials as $special) {
            Special::updateOrCreate(
                ['name' => $special['name']],
                $special
            );
        }

        $this->command->info('Specials/Promotions created successfully!');
    }
}
