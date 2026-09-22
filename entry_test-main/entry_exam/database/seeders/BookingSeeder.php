<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hotel;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch existing hotel IDs
        $hotels = Hotel::limit(10)->get();

        if ($hotels->isEmpty()) {
            return;
        }

        $sampleCustomers = [
            ['name' => '山田 太郎', 'contact' => '090-1111-2222 / taro.yamada@example.com'],
            ['name' => '佐藤 花子', 'contact' => '080-3333-4444 / hanako.sato@example.com'],
            ['name' => '鈴木 一郎', 'contact' => '070-5555-6666 / ichiro.suzuki@example.com'],
            ['name' => '高橋 健太', 'contact' => '090-7777-8888 / kenta.takahashi@example.com'],
            ['name' => '田中 裕子', 'contact' => '080-9999-0000 / yuko.tanaka@example.com'],
            ['name' => 'Nguyen Van A', 'contact' => '+84-901-234-567 / nguyenvana@example.com'],
            ['name' => 'Tran Thi B', 'contact' => '+84-912-345-678 / tranthib@example.com'],
            ['name' => 'John Smith', 'contact' => '+1-555-019-2834 / john.smith@example.com'],
            ['name' => '伊藤 誠', 'contact' => '090-1234-5678 / makoto.ito@example.com'],
            ['name' => '渡辺 美咲', 'contact' => '080-2345-6789 / misaki.watanabe@example.com'],
            ['name' => '小林 蓮', 'contact' => '070-3456-7890 / ren.kobayashi@example.com'],
            ['name' => '加藤 さくら', 'contact' => '090-4567-8901 / sakura.kato@example.com'],
            ['name' => 'Le Van C', 'contact' => '+84-933-456-789 / levanc@example.com'],
            ['name' => 'Pham Thi D', 'contact' => '+84-944-567-890 / phamthid@example.com'],
            ['name' => 'Michael Brown', 'contact' => '+1-555-024-6810 / michael.brown@example.com'],
            ['name' => 'Emily Davis', 'contact' => '+1-555-036-9125 / emily.davis@example.com'],
            ['name' => '山本 大輔', 'contact' => '090-5678-9012 / daisuke.yamamoto@example.com'],
            ['name' => '中村 葵', 'contact' => '080-6789-0123 / aoi.nakamura@example.com'],
        ];

        $bookings = [];
        $now = Carbon::now();

        foreach ($sampleCustomers as $index => $customer) {
            $hotel = $hotels[$index % $hotels->count()];
            $checkin = $now->copy()->addDays($index * 3)->setTime(15, 0, 0);
            $checkout = $checkin->copy()->addDays(2)->setTime(10, 0, 0);

            $bookings[] = [
                'hotel_id' => $hotel->hotel_id,
                'customer_name' => $customer['name'],
                'customer_contact' => $customer['contact'],
                'chekin_time' => $checkin,
                'checkout_time' => $checkout,
                'created_at' => $now->copy()->subDays($index),
                'updated_at' => $now->copy()->subDays($index),
            ];
        }

        DB::table('bookings')->insert($bookings);
    }
}

