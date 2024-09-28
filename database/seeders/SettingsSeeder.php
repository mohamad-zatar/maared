<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::truncate();

        Setting::create([
            'insurance_rate' => 5,
            'time_zone' => 'Asia/Riyadh',
            'currency' => 'SAR',
            'max_bid_amount' => 100000.00,
            'default_language' => 'en',
            'minimum_bid_amount' => 100.00,
            'refund_policy' => 'No refunds after winning.',
            'maintenance_mode' => false,
            'service_fee_percentage' => 5.00,
            'tax_rate' => 0.00,
            'website_title' => 'Auction Yard',
        ]);
    }
}
