<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodayFollowUpsWidget extends BaseWidget
{
    protected static ?int $sort = 10;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '📞 Today\'s Follow-ups - Call Schedule';
    protected static ?string $pollingInterval = '1min';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Task::query()
                    ->whereDate('due_date', today())
                    ->where('status', '!=', 'Completed')
                    ->whereIn('type', ['Call', 'Follow Up'])
                    ->with(['taskable', 'assignedAgent'])
                    ->orderBy('due_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Time')
                    ->time('h:i A')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Call' => 'success',
                        'Follow Up' => 'primary',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Task')
                    ->searchable()
                    ->limit(35),
                    
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
                    
                Tables\Columns\IconColumn::make('is_completed')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->state(fn ($record) => $record->status === 'Completed'),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_completed')
                    ->label('Complete')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'Completed')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'Completed',
                            'completed_at' => now(),
                        ]);
                    })
                    ->successNotificationTitle('Task completed'),
            ])
            ->emptyStateHeading('No follow-ups scheduled for today')
            ->emptyStateDescription('Tasks will appear here once scheduled')
            ->emptyStateIcon('heroicon-o-phone');
    }
}
