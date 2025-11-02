<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryMethod;

class DeliveryMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Fast Delivery',
                'name_kh' => 'សាជីវកម្មរហ័ស',
                'description' => 'Express delivery within 1-2 hours',
                'price' => 3.00,
                'estimated_days' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Normal Delivery',
                'name_kh' => 'ការធម្មតា',
                'description' => 'Standard delivery within 2-3 days',
                'price' => 1.00,
                'estimated_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Express Pickup',
                'name_kh' => 'យករហ័ស',
                'description' => 'Pick up at store location',
                'price' => 0.00,
                'estimated_days' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($methods as $method) {
            DeliveryMethod::updateOrCreate(
                ['name' => $method['name']],
                $method
            );
        }

        $this->command->info('Delivery methods created successfully!');
    }
}
