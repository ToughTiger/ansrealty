<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Opportunity;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueAnalyticsChart extends ChartWidget
{
    protected static ?string $heading = '📊 Revenue Analytics';
    
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'month';

    protected function getData(): array
    {
        $data = $this->getRevenueData();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (₹)',
                    'data' => $data['amounts'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
                [
                    'label' => 'Commission (₹)',
                    'data' => $data['commissions'],
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last 7 days',
            'month' => 'Last 30 days',
            'year' => 'This Year',
        ];
    }

    protected function getRevenueData(): array
    {
        $filter = $this->filter;
        
        switch ($filter) {
            case 'today':
                return $this->getTodayData();
            case 'week':
                return $this->getWeekData();
            case 'month':
                return $this->getMonthData();
            case 'year':
                return $this->getYearData();
            default:
                return $this->getMonthData();
        }
    }

    protected function getTodayData(): array
    {
        $hours = collect(range(0, 23))->map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00');
        
        $bookings = Booking::whereDate('created_at', today())
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('SUM(property_value) as total'),
                DB::raw('SUM(agent_commission_amount) as commission')
            )
            ->groupBy('hour')
            ->get()
            ->keyBy('hour');

        return [
            'labels' => $hours->toArray(),
            'amounts' => $hours->map(fn($h) => $bookings->get((int)substr($h, 0, 2))?->total ?? 0)->toArray(),
            'commissions' => $hours->map(fn($h) => $bookings->get((int)substr($h, 0, 2))?->commission ?? 0)->toArray(),
        ];
    }

    protected function getWeekData(): array
    {
        $days = collect(range(6, 0))->map(fn($d) => now()->subDays($d)->format('D'));
        
        $bookings = Booking::whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(property_value) as total'),
                DB::raw('SUM(agent_commission_amount) as commission')
            )
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        return [
            'labels' => $days->toArray(),
            'amounts' => collect(range(6, 0))->map(fn($d) => 
                $bookings->get(now()->subDays($d)->format('Y-m-d'))?->total ?? 0
            )->toArray(),
            'commissions' => collect(range(6, 0))->map(fn($d) => 
                $bookings->get(now()->subDays($d)->format('Y-m-d'))?->commission ?? 0
            )->toArray(),
        ];
    }

    protected function getMonthData(): array
    {
        $days = collect(range(29, 0))->map(fn($d) => now()->subDays($d)->format('d M'));
        
        $bookings = Booking::whereBetween('created_at', [now()->subDays(29)->startOfDay(), now()->endOfDay()])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(property_value) as total'),
                DB::raw('SUM(agent_commission_amount) as commission')
            )
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        return [
            'labels' => $days->toArray(),
            'amounts' => collect(range(29, 0))->map(fn($d) => 
                $bookings->get(now()->subDays($d)->format('Y-m-d'))?->total ?? 0
            )->toArray(),
            'commissions' => collect(range(29, 0))->map(fn($d) => 
                $bookings->get(now()->subDays($d)->format('Y-m-d'))?->commission ?? 0
            )->toArray(),
        ];
    }

    protected function getYearData(): array
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        
        $bookings = Booking::whereYear('created_at', now()->year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(property_value) as total'),
                DB::raw('SUM(agent_commission_amount) as commission')
            )
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        return [
            'labels' => $months->toArray(),
            'amounts' => $months->keys()->map(fn($m) => $bookings->get($m + 1)?->total ?? 0)->toArray(),
            'commissions' => $months->keys()->map(fn($m) => $bookings->get($m + 1)?->commission ?? 0)->toArray(),
        ];
    }
}
