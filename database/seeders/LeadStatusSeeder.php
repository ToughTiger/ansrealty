<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'New', 'slug' => 'new', 'color' => '#3b82f6', 'order' => 1],
            ['name' => 'Contacted', 'slug' => 'contacted', 'color' => '#06b6d4', 'order' => 2],
            ['name' => 'Qualified', 'slug' => 'qualified', 'color' => '#10b981', 'order' => 3],
            ['name' => 'Site Visit Planned', 'slug' => 'site-visit-planned', 'color' => '#f59e0b', 'order' => 4],
            ['name' => 'Site Visit Done', 'slug' => 'site-visit-done', 'color' => '#8b5cf6', 'order' => 5],
            ['name' => 'Negotiation', 'slug' => 'negotiation', 'color' => '#ec4899', 'order' => 6],
            ['name' => 'Converted to Opportunity', 'slug' => 'converted', 'color' => '#10b981', 'order' => 7],
            ['name' => 'Not Interested', 'slug' => 'not-interested', 'color' => '#ef4444', 'order' => 8],
            ['name' => 'Lost', 'slug' => 'lost', 'color' => '#dc2626', 'order' => 9],
        ];

        foreach ($statuses as $status) {
            DB::table('lead_statuses')->insert(array_merge($status, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
