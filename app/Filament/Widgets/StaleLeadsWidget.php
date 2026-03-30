<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StaleLeadsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $staleLeads = Lead::where('is_stale', true)
            ->whereNull('converted_at')
            ->count();

        $needsAttention = Lead::whereNull('converted_at')
            ->where('last_activity_at', '<', now()->subDays(7))
            ->where('is_stale', false)
            ->count();

        $coldLeads = Lead::whereNull('converted_at')
            ->where('priority', 'Cold')
            ->where('last_activity_at', '<', now()->subDays(3))
            ->count();

        $noFollowUp = Lead::whereNull('converted_at')
            ->whereDoesntHave('tasks', function($query) {
                $query->where('status', 'Pending')
                    ->where('due_date', '>', now());
            })
            ->count();

        return [
            Stat::make('🚨 Stale Leads (14+ days)', $staleLeads)
                ->description('No activity for 14+ days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "\$dispatch('filterStaleLeads', { type: 'stale' })",
                ]),

            Stat::make('⚠️ Needs Attention (7-14 days)', $needsAttention)
                ->description('Will become stale soon')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "\$dispatch('filterStaleLeads', { type: 'attention' })",
                ]),

            Stat::make('❄️ Cold Leads (3+ days)', $coldLeads)
                ->description('Cold priority, inactive 3+ days')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "\$dispatch('filterStaleLeads', { type: 'cold' })",
                ]),

            Stat::make('📋 No Follow-up Scheduled', $noFollowUp)
                ->description('No pending tasks')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('gray')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "\$dispatch('filterStaleLeads', { type: 'no-followup' })",
                ]),
        ];
    }

    public static function canView(): bool
    {
        return auth()->check();
    }
}
