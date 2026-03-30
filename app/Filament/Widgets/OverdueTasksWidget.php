<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class OverdueTasksWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '⚠️ Overdue Tasks - Needs Immediate Attention';
    protected static ?string $pollingInterval = '1min';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Task::query()
                    ->where('status', '!=', 'Completed')
                    ->where('due_date', '<', now())
                    ->with(['taskable', 'assignedAgent'])
                    ->orderBy('due_date')
                    ->limit(20)
            )
            ->columns([
                Tables\Columns\TextColumn::make('overdue_by')
                    ->label('Overdue')
                    ->state(function ($record) {
                        $diff = now()->diffInHours($record->due_date);
                        
                        if ($diff < 24) {
                            return $diff . 'h ago';
                        }
                        return now()->diffInDays($record->due_date) . 'd ago';
                    })
                    ->badge()
                    ->color('danger'),
                    
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Call' => 'success',
                        'Meeting' => 'info',
                        'Site Visit' => 'warning',
                        'Follow Up' => 'primary',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Task')
                    ->searchable()
                    ->weight('bold')
                    ->limit(40),
                    
                Tables\Columns\TextColumn::make('taskable')
                    ->label('Related To')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->taskable) return '—';
                        $type = class_basename($record->taskable_type);
                        if ($type === 'Lead' && $record->taskable) {
                            return $record->taskable->full_name ?? '—';
                        }
                        return $type . ' #' . $record->taskable_id;
                    })
                    ->default('—'),
                    
                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Assigned To')
                    ->badge()
                    ->color('warning'),
                    
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'High', 'Urgent' => 'danger',
                        'Medium' => 'warning',
                        'Low' => 'info',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due Date')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_completed')
                    ->label('Complete')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'Completed',
                            'completed_at' => now(),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->successNotificationTitle('Task marked as completed'),
            ])
            ->emptyStateHeading('🎉 No overdue tasks!')
            ->emptyStateDescription('All tasks are up to date')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
