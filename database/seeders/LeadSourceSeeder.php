<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadSourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            ['name' => 'Website Contact Form', 'slug' => 'website', 'color' => '#3b82f6', 'order' => 1],
            ['name' => 'Facebook Ads', 'slug' => 'facebook', 'color' => '#1877f2', 'order' => 2],
            ['name' => 'Google Ads', 'slug' => 'google', 'color' => '#ea4335', 'order' => 3],
            ['name' => 'WhatsApp', 'slug' => 'whatsapp', 'color' => '#25d366', 'order' => 4],
            ['name' => 'Walk-in', 'slug' => 'walk-in', 'color' => '#f59e0b', 'order' => 5],
            ['name' => 'Referral', 'slug' => 'referral', 'color' => '#8b5cf6', 'order' => 6],
            ['name' => 'Email Campaign', 'slug' => 'email', 'color' => '#06b6d4', 'order' => 7],
            ['name' => 'Instagram', 'slug' => 'instagram', 'color' => '#e4405f', 'order' => 8],
            ['name' => 'Property Portal', 'slug' => 'portal', 'color' => '#10b981', 'order' => 9],
            ['name' => 'Direct Call', 'slug' => 'phone', 'color' => '#6366f1', 'order' => 10],
        ];

        foreach ($sources as $source) {
            DB::table('lead_sources')->insert(array_merge($source, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
