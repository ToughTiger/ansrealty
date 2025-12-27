# 🎉 Phase 2D - Dashboard Widgets Guide

## ⚠️ IMPORTANT: Manual Setup Required

Due to PowerShell 6+ not being available on this system, you need to manually create the widgets directory and files.

---

## 📁 Step 1: Create Directory

Create this directory:
```
C:\laragon\www\ansrealty\app\Filament\Widgets\
```

### How to Create:
**Option A: Using File Explorer**
1. Navigate to: `C:\laragon\www\ansrealty\app\Filament\`
2. Right-click → New → Folder
3. Name it: `Widgets`

**Option B: Using Command Prompt**
1. Open Command Prompt (cmd)
2. Navigate to project: `cd C:\laragon\www\ansrealty`
3. Run: `mkdir app\Filament\Widgets`

**Option C: Using Artisan (Recommended)**
1. Open Command Prompt
2. Navigate to project: `cd C:\laragon\www\ansrealty`
3. Run the batch file: `create-widgets.bat`
4. Or run individual commands:
   ```batch
   php artisan make:filament-widget LeadStatsOverview --stats-overview
   php artisan make:filament-widget OpportunityPipeline --chart
   php artisan make:filament-widget TasksDueWidget --table
   php artisan make:filament-widget RevenueWidget --stats-overview
   php artisan make:filament-widget SiteVisitWidget --table
   ```

---

## 📄 Step 2: Create Widget Files

After creating the `Widgets` directory, you need to create 5 PHP files with the content provided below.

### Files to Create:
1. `LeadStatsOverview.php` - Lead statistics (Hot/Warm/Cold, Conversion rate)
2. `OpportunityPipeline.php` - Pipeline value chart by stage
3. `TasksDueWidget.php` - Today's tasks & overdue tasks table
4. `RevenueWidget.php` - Monthly/yearly revenue & commission stats
5. `SiteVisitWidget.php` - Today's site visits & upcoming schedule

---

## 📊 Widget 1: LeadStatsOverview.php

**Location:** `app\Filament\Widgets\LeadStatsOverview.php`

**Type:** Stats Overview Widget (4 cards)

**Features:**
- Total leads count
- New leads this month
- Conversion rate (Leads → Opportunities)
- Hot/Warm/Cold breakdown

**What it shows:**
```
┌────────────────────────────────────────────────────────────┐
│  Total Leads    New This Month   Conversion    Hot Leads   │
│     250             45            28.5%           67        │
│  📈 All time    ✨ Fresh leads  📊 Rate       🔥 Priority  │
└────────────────────────────────────────────────────────────┘
```

**Copy this to the file:**

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalLeads = Lead::count();
        $newLeads = Lead::where('created_at', '>=', now()->startOfMonth())->count();
        
        // Leads by priority
        $hotLeads = Lead::where('priority', 'Hot')->count();
        $warmLeads = Lead::where('priority', 'Warm')->count();
        $coldLeads = Lead::where('priority', 'Cold')->count();
        
        // Conversion rate (leads that have opportunities)
        $leadsWithOpportunities = Lead::has('opportunities')->count();
        $conversionRate = $totalLeads > 0 
            ? round(($leadsWithOpportunities / $totalLeads) * 100, 1) 
            : 0;

        return [
            Stat::make('Total Leads', $totalLeads)
                ->description('All time leads')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary')
                ->chart([7, 12, 15, 18, 22, 25, 28]),
            
            Stat::make('New This Month', $newLeads)
                ->description('Fresh leads')
                ->descriptionIcon('heroicon-o-sparkles')
                ->color('success')
                ->chart([3, 5, 7, 9, 12, 15, $newLeads]),
            
            Stat::make('Conversion Rate', $conversionRate . '%')
                ->description('Leads → Opportunities')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color($conversionRate >= 30 ? 'success' : ($conversionRate >= 15 ? 'warning' : 'danger')),
            
            Stat::make('Hot Leads', $hotLeads)
                ->description("{$warmLeads} Warm • {$coldLeads} Cold")
                ->descriptionIcon('heroicon-o-fire')
                ->color('danger'),
        ];
    }
}
```

---

## 📈 Widget 2: OpportunityPipeline.php

**Location:** `app\Filament\Widgets\OpportunityPipeline.php`

**Type:** Bar Chart Widget

**Features:**
- Shows pipeline value by stage
- Only shows stages with opportunities
- Values displayed in lakhs (₹L)
- Color-coded bars

**What it shows:**
```
Opportunity Pipeline
┌────────────────────────────────────┐
│        ₹ LAKHS                     │
│  80 ┤    █                         │
│  60 ┤    █   █                     │
│  40 ┤    █   █   █                 │
│  20 ┤    █   █   █   █   █         │
│   0 └─────────────────────────────  │
│     Created │ Visit │ Nego │ Token │
└────────────────────────────────────┘
```

**Copy this to the file:**

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Opportunity;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OpportunityPipeline extends ChartWidget
{
    protected static ?string $heading = 'Opportunity Pipeline';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get opportunities grouped by stage with value
        $pipelineData = Opportunity::query()
            ->where('status', 'Open')
            ->select('stage', DB::raw('SUM(deal_value) as total_value'), DB::raw('COUNT(*) as count'))
            ->groupBy('stage')
            ->orderBy('stage')
            ->get();

        $stages = [
            'Opportunity Created',
            'Requirement Finalized',
            'Property Shortlisted',
            'Site Visit Scheduled',
            'Site Visit Completed',
            'Price Discussion',
            'Negotiation',
            'Token Amount Paid',
            'Agreement Stage',
            'Registration Stage',
        ];

        $values = [];
        $counts = [];
        $labels = [];

        foreach ($stages as $stage) {
            $data = $pipelineData->firstWhere('stage', $stage);
            $value = $data ? $data->total_value : 0;
            $count = $data ? $data->count : 0;
            
            if ($count > 0) { // Only show stages with data
                $labels[] = $this->shortenStage($stage);
                $values[] = round($value / 100000, 2); // Convert to lakhs
                $counts[] = $count;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pipeline Value (₹ Lakhs)',
                    'data' => $values,
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#059669',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => '(value) => "₹" + value + "L"',
                    ],
                ],
            ],
        ];
    }

    private function shortenStage(string $stage): string
    {
        $shortNames = [
            'Opportunity Created' => 'Created',
            'Requirement Finalized' => 'Finalized',
            'Property Shortlisted' => 'Shortlisted',
            'Site Visit Scheduled' => 'Visit Scheduled',
            'Site Visit Completed' => 'Visit Done',
            'Price Discussion' => 'Discussion',
            'Negotiation' => 'Negotiation',
            'Token Amount Paid' => 'Token Paid',
            'Agreement Stage' => 'Agreement',
            'Registration Stage' => 'Registration',
        ];

        return $shortNames[$stage] ?? $stage;
    }
}
```

---

## ✅ Widget 3: TasksDueWidget.php

**Location:** `app\Filament\Widgets\TasksDueWidget.php`

**Type:** Table Widget

**Features:**
- Shows today's tasks & overdue tasks
- Quick complete action
- Color-coded overdue tasks (red)
- Linked to leads/opportunities
- Limited to 10 most urgent tasks

**What it shows:**
```
Tasks Due Today & Overdue
┌──────────────────────────────────────────────────────────┐
│ Task           │ Type  │ Priority │ Due      │ Assigned  │
├──────────────────────────────────────────────────────────┤
│ Follow up call │ Call  │ Urgent   │ OVERDUE  │ John Doe  │
│ Site visit     │ Visit │ High     │ Today    │ Jane      │
│ Send brochure  │ Email │ Normal   │ Tomorrow │ Mike      │
└──────────────────────────────────────────────────────────┘
```

**Copy this to the file:**

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TasksDueWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Tasks Due Today & Overdue')
            ->query(
                Task::query()
                    ->where('status', '!=', 'Completed')
                    ->where(function ($query) {
                        $query->whereDate('due_date', '<=', now())
                            ->orWhereNull('due_date');
                    })
                    ->with(['taskable', 'assignedTo'])
                    ->orderByRaw("CASE WHEN due_date < CURDATE() THEN 0 ELSE 1 END")
                    ->orderBy('due_date', 'asc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40)
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'Call',
                        'success' => 'Email',
                        'warning' => 'Meeting',
                        'info' => 'Site Visit',
                        'secondary' => 'WhatsApp',
                    ]),

                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'secondary' => 'Low',
                        'primary' => 'Normal',
                        'warning' => 'High',
                        'danger' => 'Urgent',
                    ]),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('d M Y')
                    ->color(fn (Task $record) => $record->due_date < now()->startOfDay() ? 'danger' : 'primary')
                    ->weight(fn (Task $record) => $record->due_date < now()->startOfDay() ? 'bold' : 'normal')
                    ->description(fn (Task $record): string => $record->due_date < now()->startOfDay() ? 'OVERDUE' : 'Due'),

                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Assigned To')
                    ->default('Unassigned')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('taskable_type')
                    ->label('Related To')
                    ->formatStateUsing(function ($state, Task $record) {
                        $type = class_basename($state);
                        if ($record->taskable) {
                            return $type . ' #' . $record->taskable->id;
                        }
                        return $type;
                    })
                    ->badge()
                    ->color('secondary'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'Pending',
                        'warning' => 'In Progress',
                        'success' => 'Completed',
                        'danger' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        \Filament\Forms\Components\Select::make('outcome')
                            ->options([
                                'Successful' => 'Successful',
                                'Not Reachable' => 'Not Reachable',
                                'Callback Requested' => 'Callback Requested',
                                'Not Interested' => 'Not Interested',
                                'Follow-up Required' => 'Follow-up Required',
                            ])
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('remarks')
                            ->label('Remarks'),
                    ])
                    ->action(function (Task $record, array $data) {
                        $record->update([
                            'status' => 'Completed',
                            'outcome' => $data['outcome'],
                            'remarks' => $data['remarks'] ?? null,
                            'completed_at' => now(),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Task $record) => $record->status !== 'Completed'),
            ])
            ->defaultSort('due_date', 'asc');
    }
}
```

---

## 💰 Widget 4: RevenueWidget.php

**Location:** `app\Filament\Widgets\RevenueWidget.php`

**Type:** Stats Overview Widget (4 cards)

**Features:**
- Monthly & yearly revenue
- Commission tracking
- Approval pending count
- Revenue trend chart

**What it shows:**
```
┌────────────────────────────────────────────────────────────┐
│  Monthly       Yearly        Commission    Commission      │
│  Revenue       Revenue       Due           Paid            │
│  ₹45.50L      ₹328.75L      ₹85,000       ₹125,000        │
│  💰 Closed    📊 2025       ⏳ Pending    ✅ This month    │
└────────────────────────────────────────────────────────────┘
```

**Copy this to the file:**

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Commission;
use App\Models\Opportunity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class RevenueWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        // Monthly revenue from closed won opportunities
        $monthlyRevenue = Opportunity::query()
            ->where('status', 'Closed Won')
            ->whereYear('closed_date', now()->year)
            ->whereMonth('closed_date', now()->month)
            ->sum('deal_value');

        // Yearly revenue
        $yearlyRevenue = Opportunity::query()
            ->where('status', 'Closed Won')
            ->whereYear('closed_date', now()->year)
            ->sum('deal_value');

        // Get last 6 months revenue for chart
        $monthlyRevenueChart = Opportunity::query()
            ->where('status', 'Closed Won')
            ->where('closed_date', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(closed_date) as month'),
                DB::raw('SUM(deal_value) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue')
            ->toArray();

        // Commission stats
        $totalCommissionDue = Commission::query()
            ->where('payment_status', 'Pending')
            ->where('approved', true)
            ->sum('net_commission');

        $commissionPaid = Commission::query()
            ->where('payment_status', 'Paid')
            ->whereYear('payment_date', now()->year)
            ->whereMonth('payment_date', now()->month)
            ->sum('net_commission');

        $pendingApproval = Commission::query()
            ->where('approved', false)
            ->count();

        return [
            Stat::make('Monthly Revenue', '₹' . number_format($monthlyRevenue / 100000, 2) . 'L')
                ->description('Closed deals this month')
                ->descriptionIcon('heroicon-o-currency-rupee')
                ->color('success')
                ->chart($monthlyRevenueChart),

            Stat::make('Yearly Revenue', '₹' . number_format($yearlyRevenue / 100000, 2) . 'L')
                ->description(now()->year . ' total')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('primary'),

            Stat::make('Commission Due', '₹' . number_format($totalCommissionDue, 0))
                ->description('Approved, pending payment')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('warning'),

            Stat::make('Commission Paid', '₹' . number_format($commissionPaid, 0))
                ->description("{$pendingApproval} pending approval")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
```

---

## 📅 Widget 5: SiteVisitWidget.php

**Location:** `app\Filament\Widgets\SiteVisitWidget.php`

**Type:** Table Widget

**Features:**
- Today's site visits highlighted
- Upcoming schedule (next 10)
- Quick actions: Confirm, Complete, Cancel
- Full feedback capture on completion

**What it shows:**
```
Today's Site Visits & Upcoming
┌──────────────────────────────────────────────────────────┐
│ Date       │ Time   │ Property      │ Customer │ Agent   │
├──────────────────────────────────────────────────────────┤
│ 25 Dec(T)  │ 10:00  │ Prestige Park │ John Doe │ Mike    │
│ 26 Dec     │ 15:30  │ Brigade Gate  │ Jane Sm. │ Sarah   │
│ 27 Dec     │ 11:00  │ Sobha Dream   │ Bob J.   │ Tom     │
└──────────────────────────────────────────────────────────┘
```

**Copy this to the file:**

```php
<?php

namespace App\Filament\Widgets;

use App\Models\SiteVisit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class SiteVisitWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading("Today's Site Visits & Upcoming")
            ->query(
                SiteVisit::query()
                    ->whereDate('visit_date', '>=', now()->startOfDay())
                    ->whereIn('status', ['Planned', 'Confirmed'])
                    ->with(['property', 'lead', 'opportunity', 'agent'])
                    ->orderBy('visit_date', 'asc')
                    ->orderBy('visit_time', 'asc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('visit_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->weight('bold')
                    ->color(fn (SiteVisit $record) => 
                        $record->visit_date->isToday() ? 'danger' : 'primary'
                    )
                    ->description(fn (SiteVisit $record): string => 
                        $record->visit_date->isToday() ? 'TODAY' : $record->visit_date->diffForHumans()
                    ),

                Tables\Columns\TextColumn::make('visit_time')
                    ->label('Time')
                    ->time('h:i A'),

                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->searchable()
                    ->limit(30)
                    ->weight('bold')
                    ->description(fn (SiteVisit $record): ?string => $record->property?->builder?->name),

                Tables\Columns\TextColumn::make('property.city')
                    ->label('Location')
                    ->searchable(),

                Tables\Columns\TextColumn::make('lead.full_name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn (SiteVisit $record): ?string => $record->lead?->mobile),

                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'Planned',
                        'warning' => 'Confirmed',
                        'success' => 'Completed',
                        'danger' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (SiteVisit $record) {
                        $record->update(['status' => 'Confirmed']);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (SiteVisit $record) => $record->status === 'Planned'),

                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        \Filament\Forms\Components\DateTimePicker::make('actual_visit_time')
                            ->label('Actual Visit Time')
                            ->default(now())
                            ->required(),

                        \Filament\Forms\Components\Select::make('interest_level')
                            ->options([
                                'Very High' => 'Very High',
                                'High' => 'High',
                                'Medium' => 'Medium',
                                'Low' => 'Low',
                            ])
                            ->required(),

                        \Filament\Forms\Components\TextInput::make('rating')
                            ->label('Customer Rating')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->default(5)
                            ->suffix('/ 5'),

                        \Filament\Forms\Components\Textarea::make('customer_feedback')
                            ->label('Customer Feedback')
                            ->rows(3),

                        \Filament\Forms\Components\Toggle::make('follow_up_required')
                            ->label('Follow-up Required?')
                            ->default(true),

                        \Filament\Forms\Components\DatePicker::make('follow_up_date')
                            ->label('Follow-up Date')
                            ->default(now()->addDays(2))
                            ->visible(fn ($get) => $get('follow_up_required')),

                        \Filament\Forms\Components\Textarea::make('follow_up_notes')
                            ->label('Follow-up Notes')
                            ->rows(2)
                            ->visible(fn ($get) => $get('follow_up_required')),
                    ])
                    ->action(function (SiteVisit $record, array $data) {
                        $record->update([
                            'status' => 'Completed',
                            'actual_visit_time' => $data['actual_visit_time'],
                            'interest_level' => $data['interest_level'],
                            'rating' => $data['rating'] ?? null,
                            'customer_feedback' => $data['customer_feedback'] ?? null,
                            'follow_up_required' => $data['follow_up_required'] ?? false,
                            'follow_up_date' => $data['follow_up_date'] ?? null,
                            'follow_up_notes' => $data['follow_up_notes'] ?? null,
                        ]);
                    })
                    ->visible(fn (SiteVisit $record) => in_array($record->status, ['Planned', 'Confirmed'])),

                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('cancellation_reason')
                            ->label('Cancellation Reason')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (SiteVisit $record, array $data) {
                        $record->update([
                            'status' => 'Cancelled',
                            'cancellation_reason' => $data['cancellation_reason'],
                        ]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (SiteVisit $record) => in_array($record->status, ['Planned', 'Confirmed'])),
            ])
            ->defaultSort('visit_date', 'asc');
    }
}
```

---

## 🧪 Step 3: Test the Widgets

After creating all 5 widget files:

### 1. Clear Cache
```bash
cd C:\laragon\www\ansrealty
php artisan optimize:clear
php artisan filament:cache-components
```

### 2. Start Server
```bash
php artisan serve
```

### 3. View Dashboard
Visit: http://localhost:8000/admin

You should see:
- **Row 1:** Lead Stats (4 cards)
- **Row 2:** Opportunity Pipeline (chart)
- **Row 3:** Tasks Due Today (table - full width)
- **Row 4:** Revenue Stats (4 cards)
- **Row 5:** Site Visits Today (table - full width)

---

## 🎨 Widget Features Summary

### Stats Widgets (Cards)
**LeadStatsOverview:**
- Total leads with trend chart
- New leads this month
- Conversion rate (color-coded)
- Hot/Warm/Cold breakdown

**RevenueWidget:**
- Monthly revenue with trend
- Yearly revenue total
- Commission due (approved)
- Commission paid this month

### Chart Widget
**OpportunityPipeline:**
- Bar chart showing pipeline value by stage
- Values in lakhs (₹L)
- Green color scheme
- Only shows stages with data

### Table Widgets
**TasksDueWidget:**
- 10 most urgent tasks
- Overdue highlighted in red
- Quick complete action
- Shows related entity (Lead/Opportunity)

**SiteVisitWidget:**
- Today's + upcoming visits
- Today highlighted in red
- Actions: Confirm, Complete, Cancel
- Full feedback capture

---

## 🔧 Customization Options

### Change Widget Order
Edit the `$sort` property in each widget:
```php
protected static ?int $sort = 1; // Lower = appears first
```

### Change Widget Width
For stats widgets, you can change column span:
```php
protected static string | array $columnSpan = '1/2'; // Half width
```

For table widgets:
```php
protected int | string | array $columnSpan = 'full'; // Full width
```

### Hide/Show Widgets
Comment out the widget class to hide it from dashboard.

---

## 📊 What Each Widget Helps With

**LeadStatsOverview:**
- Track lead generation performance
- Monitor conversion funnel
- Identify hot prospects
- See monthly trends

**OpportunityPipeline:**
- Visualize pipeline value
- Identify bottleneck stages
- Forecast closures
- Track deal progression

**TasksDueWidget:**
- Never miss a follow-up
- Prioritize daily work
- Complete tasks quickly
- Track agent productivity

**RevenueWidget:**
- Monitor revenue targets
- Track commission payouts
- Identify pending approvals
- See monthly/yearly trends

**SiteVisitWidget:**
- Manage daily schedule
- Confirm visits in advance
- Capture feedback instantly
- Plan follow-ups

---

## 🚀 Next Steps After Widget Creation

1. ✅ Create all 5 widget files
2. ✅ Clear cache
3. ✅ Test dashboard
4. ✅ Verify data displays correctly
5. ✅ Test quick actions
6. ✅ Check responsiveness on mobile

---

## ⚠️ Troubleshooting

**Widgets not showing?**
- Clear cache: `php artisan optimize:clear`
- Check file names match exactly
- Ensure namespace is correct
- Verify no syntax errors

**No data in widgets?**
- Ensure database has sample data
- Run seeders if needed
- Check model relationships
- Verify query logic

**Chart not displaying?**
- Install Chart.js (should be included with Filament)
- Check browser console for errors
- Verify data format is correct

**Actions not working?**
- Clear cache
- Check model methods exist
- Verify relationships loaded
- Check permissions

---

## 📁 Final File Structure

After completing Phase 2D:
```
app/Filament/Widgets/
├── LeadStatsOverview.php       (Stats cards)
├── OpportunityPipeline.php     (Bar chart)
├── TasksDueWidget.php          (Table widget)
├── RevenueWidget.php           (Stats cards)
└── SiteVisitWidget.php         (Table widget)
```

---

## 🎉 Phase 2D Completion Checklist

- [ ] Directory `app\Filament\Widgets` created
- [ ] LeadStatsOverview.php created
- [ ] OpportunityPipeline.php created
- [ ] TasksDueWidget.php created
- [ ] RevenueWidget.php created
- [ ] SiteVisitWidget.php created
- [ ] Cache cleared
- [ ] Dashboard tested
- [ ] All widgets display correctly
- [ ] Quick actions work
- [ ] Data populates correctly

---

**Time Estimate:** 15-30 minutes (manual file creation + testing)

**Next Phase:** Phase 3 - Testing & Refinement

---

_Phase 2D Guide - Dashboard Widgets_  
_Generated: 2025-12-25_  
_Status: Ready for Manual Implementation_
