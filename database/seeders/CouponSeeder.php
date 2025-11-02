<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'SAVE20',
                'type' => 'percentage',
                'value' => 20.00,
                'min_order_amount' => 10.00,
                'max_discount_amount' => null,
                'usage_limit' => null,
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'FIRST5',
                'type' => 'fixed',
                'value' => 5.00,
                'min_order_amount' => 20.00,
                'max_discount_amount' => null,
                'usage_limit' => 100,
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'MEGA50',
                'type' => 'percentage',
                'value' => 50.00,
                'min_order_amount' => 50.00,
                'max_discount_amount' => 20.00,
                'usage_limit' => 50,
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(1),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'fixed',
                'value' => 1.00,
                'min_order_amount' => 15.00,
                'max_discount_amount' => null,
                'usage_limit' => null,
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addYears(1),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }

        $this->command->info('Coupons created successfully!');
    }
}
