<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpportunityStageSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            ['name' => 'Opportunity Created', 'slug' => 'created', 'color' => '#3b82f6', 'probability' => 10, 'order' => 1],
            ['name' => 'Requirement Finalized', 'slug' => 'requirement-finalized', 'color' => '#06b6d4', 'probability' => 20, 'order' => 2],
            ['name' => 'Property Shortlisted', 'slug' => 'property-shortlisted', 'color' => '#10b981', 'probability' => 30, 'order' => 3],
            ['name' => 'Site Visit Scheduled', 'slug' => 'site-visit-scheduled', 'color' => '#f59e0b', 'probability' => 40, 'order' => 4],
            ['name' => 'Site Visit Completed', 'slug' => 'site-visit-completed', 'color' => '#8b5cf6', 'probability' => 50, 'order' => 5],
            ['name' => 'Price Discussion', 'slug' => 'price-discussion', 'color' => '#ec4899', 'probability' => 60, 'order' => 6],
            ['name' => 'Negotiation', 'slug' => 'negotiation', 'color' => '#f97316', 'probability' => 70, 'order' => 7],
            ['name' => 'Token Amount Paid', 'slug' => 'token-paid', 'color' => '#a855f7', 'probability' => 80, 'order' => 8],
            ['name' => 'Agreement Stage', 'slug' => 'agreement', 'color' => '#6366f1', 'probability' => 90, 'order' => 9],
            ['name' => 'Registration Stage', 'slug' => 'registration', 'color' => '#8b5cf6', 'probability' => 95, 'order' => 10],
            ['name' => 'Closed Won', 'slug' => 'closed-won', 'color' => '#22c55e', 'probability' => 100, 'order' => 11],
            ['name' => 'Closed Lost', 'slug' => 'closed-lost', 'color' => '#ef4444', 'probability' => 0, 'order' => 12],
        ];

        foreach ($stages as $stage) {
            DB::table('opportunity_stages')->insert(array_merge($stage, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
