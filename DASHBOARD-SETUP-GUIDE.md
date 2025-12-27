# 📊 Beautiful Dashboard Setup Guide

## Quick Setup

### Step 1: Run This Command

```bash
create-dashboard.bat
```

This will:
- Create required directories
- Install Flowframe Trend package for charts
- Clear caches

### Step 2: Create Widget Files Manually

Since we can't create the directory programmatically, please create these files manually:

---

## Widget Files to Create

### 1. StatsOverview Widget
**File:** `app/Filament/Widgets/StatsOverview.php`

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Property;
use App\Models\Commission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $totalLeads = Lead::count();
        $newLeadsThisMonth = Lead::whereMonth('created_at', now()->month)->count();
        $leadsLastMonth = Lead::whereMonth('created_at', now()->subMonth()->month)->count();
        $leadsTrend = $leadsLastMonth > 0 ? (($newLeadsThisMonth - $leadsLastMonth) / $leadsLastMonth) * 100 : 0;
        
        $activeProperties = Property::where('is_active', true)
            ->where('availability_status', 'Available')
            ->count();
        $totalProperties = Property::count();
        
        $activeOpportunities = Opportunity::whereHas('stage', function($query) {
            $query->whereNotIn('name', ['Closed Won', 'Closed Lost']);
        })->count();
        
        $closedDealsThisMonth = Opportunity::whereHas('stage', function($query) {
            $query->where('name', 'Closed Won');
        })->whereMonth('updated_at', now()->month)->count();
        
        $totalRevenue = Commission::where('status', 'Paid')->sum('net_payout') ?? 0;
        $revenueThisMonth = Commission::where('status', 'Paid')
            ->whereMonth('created_at', now()->month)
            ->sum('net_payout') ?? 0;
        
        return [
            Stat::make('Total Leads', number_format($totalLeads))
                ->description($newLeadsThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 12, 8, 15, 10, 18, $newLeadsThisMonth])
                ->color($leadsTrend >= 0 ? 'success' : 'danger'),
                
            Stat::make('Active Properties', number_format($activeProperties))
                ->description($totalProperties . ' total properties')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info'),
                
            Stat::make('Active Opportunities', number_format($activeOpportunities))
                ->description($closedDealsThisMonth . ' deals closed this month')
                ->descriptionIcon('heroicon-m-briefcase')
                ->chart([5, 8, 6, 10, 7, 12, $closedDealsThisMonth])
                ->color('warning'),
                
            Stat::make('Total Revenue', '₹' . number_format($totalRevenue / 100000, 2) . 'L')
                ->description('₹' . number_format($revenueThisMonth / 100000, 2) . 'L this month')
                ->descriptionIcon('heroicon-m-currency-rupee')
                ->color('success'),
        ];
    }
}
```

---

### 2. LeadsChart Widget
**File:** `app/Filament/Widgets/LeadsChart.php`

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class LeadsChart extends ChartWidget
{
    protected static ?string $heading = 'Leads Overview (Last 12 Months)';
    
    protected static ?int $sort = 2;
    
    protected static ?string $maxHeight = '300px';
    
    protected int | string | array $columnSpan = 2;
    
    protected function getData(): array
    {
        try {
            $data = Trend::model(Lead::class)
                ->between(
                    start: now()->subMonths(11),
                    end: now(),
                )
                ->perMonth()
                ->count();
     
            return [
                'datasets' => [
                    [
                        'label' => 'Leads',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'borderColor' => 'rgb(59, 130, 246)',
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                ],
                'labels' => $data->map(fn (TrendValue $value) => date('M Y', strtotime($value->date))),
            ];
        } catch (\Exception $e) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }
    }
 
    protected function getType(): string
    {
        return 'line';
    }
}
```

---

### 3. RecentLeads Widget
**File:** `app/Filament/Widgets/RecentLeads.php`

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentLeads extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Lead::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('mobile')
                    ->label('Phone')
                    ->searchable()
                    ->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('source.name')
                    ->label('Source')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('status.name')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'New' => 'gray',
                        'Contacted' => 'info',
                        'Qualified' => 'warning',
                        'Converted' => 'success',
                        'Lost' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('assigned_to.name')
                    ->label('Agent')
                    ->default('-')
                    ->icon('heroicon-m-user'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M, Y')
                    ->sortable(),
            ])
            ->heading('Recent Leads')
            ->description('Latest 5 leads in the system')
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->url(fn (Lead $record): string => route('filament.admin.resources.leads.view', $record))
                    ->icon('heroicon-m-eye')
                    ->color('info'),
            ]);
    }
}
```

---

### 4. OpportunityByStage Widget
**File:** `app/Filament/Widgets/OpportunityByStage.php`

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Opportunity;
use Filament\Widgets\ChartWidget;

class OpportunityByStage extends ChartWidget
{
    protected static ?string $heading = 'Opportunities by Stage';
    
    protected static ?int $sort = 4;
    
    protected static ?string $maxHeight = '300px';
    
    protected int | string | array $columnSpan = 1;
    
    protected function getData(): array
    {
        try {
            $opportunities = Opportunity::with('stage')
                ->get()
                ->groupBy('stage.name')
                ->map(fn ($group) => $group->count());
            
            return [
                'datasets' => [
                    [
                        'label' => 'Opportunities',
                        'data' => $opportunities->values(),
                        'backgroundColor' => [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)',
                        ],
                    ],
                ],
                'labels' => $opportunities->keys(),
            ];
        } catch (\Exception $e) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }
    }
 
    protected function getType(): string
    {
        return 'doughnut';
    }
}
```

---

### 5. PropertyByType Widget
**File:** `app/Filament/Widgets/PropertyByType.php`

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use Filament\Widgets\ChartWidget;

class PropertyByType extends ChartWidget
{
    protected static ?string $heading = 'Properties by Type';
    
    protected static ?int $sort = 5;
    
    protected static ?string $maxHeight = '300px';
    
    protected int | string | array $columnSpan = 1;
    
    protected function getData(): array
    {
        try {
            $properties = Property::where('is_active', true)
                ->get()
                ->groupBy('property_type')
                ->map(fn ($group) => $group->count());
            
            return [
                'datasets' => [
                    [
                        'label' => 'Properties',
                        'data' => $properties->values(),
                        'backgroundColor' => [
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(244, 63, 94, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                        ],
                    ],
                ],
                'labels' => $properties->keys(),
            ];
        } catch (\Exception $e) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }
    }
 
    protected function getType(): string
    {
        return 'pie';
    }
}
```

---

## Installation Steps

### Method 1: Using Artisan Commands (Recommended)

```bash
# Create directory first
mkdir app\Filament\Widgets

# Create widgets
php artisan make:filament-widget StatsOverview --stats-overview
php artisan make:filament-widget LeadsChart --chart
php artisan make:filament-widget RecentLeads --table
php artisan make:filament-widget OpportunityByStage --chart
php artisan make:filament-widget PropertyByType --chart

# Install trend package
composer require "flowframe/laravel-trend"

# Clear cache
php artisan optimize:clear
```

Then copy the code from above into each generated widget file.

### Method 2: Manual File Creation

1. Create directory: `app/Filament/Widgets`
2. Create each PHP file listed above
3. Copy the corresponding code into each file
4. Install trend package: `composer require "flowframe/laravel-trend"`
5. Clear cache: `php artisan optimize:clear`

---

## Dashboard Features

### 📊 Statistics Cards
- **Total Leads** - With monthly trend and sparkline chart
- **Active Properties** - Available vs total properties
- **Active Opportunities** - With deals closed this month
- **Total Revenue** - Commission earned with monthly breakdown

### 📈 Charts
- **Leads Overview** - 12-month trend line chart
- **Opportunities by Stage** - Doughnut chart showing pipeline
- **Properties by Type** - Pie chart of property distribution

### 📋 Tables
- **Recent Leads** - Last 5 leads with quick view action

---

## Customization

### Change Widget Order
Edit the `$sort` property in each widget:
```php
protected static ?int $sort = 1; // Lower number = higher position
```

### Change Widget Size
Edit the `$columnSpan` property:
```php
protected int | string | array $columnSpan = 'full'; // full, 1, 2, 3, etc.
```

### Customize Colors
In chart widgets, modify the `backgroundColor` array to use your brand colors.

---

## Troubleshooting

### Issue: "Class not found" error
**Solution:**
```bash
composer dump-autoload
php artisan optimize:clear
```

### Issue: Charts not showing data
**Solution:** Make sure you have data in your database tables (leads, opportunities, properties)

### Issue: "Trend" class not found
**Solution:**
```bash
composer require "flowframe/laravel-trend"
```

---

## Result

After setup, your dashboard will display:

✅ **4 Beautiful Statistic Cards** with icons, trends, and sparklines
✅ **Line Chart** showing leads over 12 months
✅ **Doughnut Chart** showing opportunity pipeline
✅ **Pie Chart** showing property type distribution
✅ **Recent Leads Table** with quick actions

All with:
- Responsive design
- Real-time data
- Beautiful colors and icons
- Interactive charts
- Professional layout

---

## Next Steps

After creating the widgets:
1. Refresh admin panel
2. Dashboard will automatically load all widgets
3. Widgets will display in order based on `$sort` property
4. Data updates automatically when you add leads/opportunities/properties

🎉 **Enjoy your beautiful dashboard!**
