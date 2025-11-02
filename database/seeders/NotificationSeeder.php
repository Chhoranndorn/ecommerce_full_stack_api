<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user (you can change this to specific user_id)
        $user = User::first();

        if (!$user) {
            $this->command->warn('No users found. Please create a user first.');
            return;
        }

        $notifications = [
            // Promotion Notifications
            [
                'user_id' => $user->id,
                'type' => 'promotion',
                'title' => 'New Promotion Available!',
                'title_kh' => 'ប្រូម៉ូសិនថ្មី',
                'message' => 'Get 50% off on all delicious recipes. Limited time offer!',
                'message_kh' => 'ទទួលបានការបញ្ចុះតម្លៃ 50% លើរូបមន្តឆ្ងាញ់ទាំងអស់។ ការផ្តល់ជូនពេលកំណត់!',
                'data' => [
                    'promotion_id' => 1,
                    'discount' => '50%',
                ],
                'image' => null,
                'is_read' => false,
                'created_at' => Carbon::parse('2025-01-19 10:30 AM'),
            ],
            [
                'user_id' => $user->id,
                'type' => 'promotion',
                'title' => 'Weekend Special',
                'title_kh' => 'ប្រូម៉ូសិនពិសេស',
                'message' => 'Enjoy 30% off on all weekend orders. Perfect for family gatherings!',
                'message_kh' => 'រីករាយជាមួយការបញ្ចុះតម្លៃ 30% សម្រាប់ការបញ្ជាទិញចុងសប្តាហ៍ទាំងអស់។',
                'data' => [
                    'promotion_id' => 2,
                    'discount' => '30%',
                ],
                'image' => null,
                'is_read' => false,
                'created_at' => Carbon::parse('2025-01-19 4:51 PM'),
            ],

            // Transaction/Order Notifications
            [
                'user_id' => $user->id,
                'type' => 'transaction',
                'title' => 'Order Confirmed',
                'title_kh' => 'បញ្ជាទិញបានបញ្ជាក់',
                'message' => 'Your order #ORD-000001 has been confirmed and is being prepared.',
                'message_kh' => 'ការបញ្ជាទិញរបស់អ្នក #ORD-000001 ត្រូវបានបញ្ជាក់ និងកំពុងត្រូវបានរៀបចំ។',
                'data' => [
                    'order_id' => 1,
                    'order_number' => 'ORD-000001',
                    'total' => 15.00,
                    'items' => [
                        ['name' => 'សាច់ក្រុកប្រុស', 'qty' => 1],
                        ['name' => 'ចំណុះស្រស់មុំ គុយ', 'qty' => 1],
                        ['name' => 'កាហ្វេត្រជាក់', 'qty' => 1],
                    ],
                ],
                'image' => null,
                'is_read' => false,
                'created_at' => Carbon::parse('2025-01-18 9:37 AM'),
            ],
            [
                'user_id' => $user->id,
                'type' => 'transaction',
                'title' => 'Order Delivered',
                'title_kh' => 'បញ្ជាទិញបានដឹកជញ្ជូន',
                'message' => 'Your order #ORD-000002 has been delivered successfully.',
                'message_kh' => 'ការបញ្ជាទិញរបស់អ្នក #ORD-000002 ត្រូវបានដឹកជញ្ជូនដោយជោគជ័យ។',
                'data' => [
                    'order_id' => 2,
                    'order_number' => 'ORD-000002',
                    'status' => 'delivered',
                ],
                'image' => null,
                'is_read' => true,
                'created_at' => Carbon::parse('2025-01-17 3:20 PM'),
            ],
            [
                'user_id' => $user->id,
                'type' => 'transaction',
                'title' => 'Payment Received',
                'title_kh' => 'បានទទួលការទូទាត់',
                'message' => 'We have received your payment for order #ORD-000003. Thank you!',
                'message_kh' => 'យើងបានទទួលការទូទាត់របស់អ្នកសម្រាប់ការបញ្ជាទិញ #ORD-000003។ សូមអរគុណ!',
                'data' => [
                    'order_id' => 3,
                    'order_number' => 'ORD-000003',
                    'amount' => 25.00,
                ],
                'image' => null,
                'is_read' => true,
                'created_at' => Carbon::parse('2025-01-16 11:45 AM'),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        $this->command->info('Notifications created successfully!');
        $this->command->info('User: ' . $user->name . ' (' . $user->email . ')');
    }
}
