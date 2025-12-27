<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    
    protected static ?string $navigationGroup = 'Activities';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Task Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('task_type')
                            ->options([
                                'call' => 'Call',
                                'email' => 'Email',
                                'meeting' => 'Meeting',
                                'site_visit' => 'Site Visit',
                                'follow_up' => 'Follow-up',
                                'documentation' => 'Documentation',
                                'other' => 'Other',
                            ])
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'normal' => 'Normal',
                                'high' => 'High',
                                'urgent' => 'Urgent',
                            ])
                            ->default('normal')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Assignment & Timeline')
                    ->schema([
                        Forms\Components\Select::make('assigned_to')
                            ->label('Assigned Agent')
                            ->relationship('assignedAgent', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required()
                            ->live()
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('due_date')
                            ->label('Due Date & Time')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Completed At')
                            ->native(false)
                            ->seconds(false)
                            ->visible(fn ($get) => $get('status') === 'completed')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Related To')
                    ->schema([
                        Forms\Components\Select::make('taskable_type')
                            ->label('Task Related To')
                            ->options([
                                'App\\Models\\Lead' => 'Lead',
                                'App\\Models\\Opportunity' => 'Opportunity',
                            ])
                            ->live()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('taskable_id')
                            ->label('Select Record')
                            ->options(function ($get) {
                                $type = $get('taskable_type');
                                if ($type === 'App\\Models\\Lead') {
                                    return \App\Models\Lead::pluck('full_name', 'id');
                                } elseif ($type === 'App\\Models\\Opportunity') {
                                    return \App\Models\Opportunity::pluck('title', 'id');
                                }
                                return [];
                            })
                            ->searchable()
                            ->required()
                            ->visible(fn ($get) => filled($get('taskable_type')))
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Results')
                    ->schema([
                        Forms\Components\Textarea::make('result')
                            ->label('Task Result/Outcome')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('remarks')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($get) => in_array($get('status'), ['completed', 'cancelled']))
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40)
                    ->weight('bold'),
                Tables\Columns\BadgeColumn::make('task_type')
                    ->label('Type')
                    ->colors([
                        'info' => 'call',
                        'primary' => 'email',
                        'warning' => 'meeting',
                        'success' => 'site_visit',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state))),
                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'gray' => 'low',
                        'info' => 'normal',
                        'warning' => 'high',
                        'danger' => 'urgent',
                    ]),
                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Assigned To')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('taskable_type')
                    ->label('Related To')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->color(function ($record) {
                        if ($record->status === 'completed') return 'success';
                        return $record->due_date < now() ? 'danger' : 'gray';
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state))),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('task_type')
                    ->label('Type')
                    ->options([
                        'call' => 'Call',
                        'email' => 'Email',
                        'meeting' => 'Meeting',
                        'site_visit' => 'Site Visit',
                        'follow_up' => 'Follow-up',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'normal' => 'Normal',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label('Assigned To')
                    ->relationship('assignedAgent', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue')
                    ->query(fn (EloquentBuilder $query): EloquentBuilder => $query->where('due_date', '<', now())->where('status', '!=', 'completed')),
                Tables\Filters\Filter::make('today')
                    ->label('Due Today')
                    ->query(fn (EloquentBuilder $query): EloquentBuilder => $query->whereDate('due_date', today())),
                Tables\Filters\Filter::make('high_priority')
                    ->label('High Priority')
                    ->query(fn (EloquentBuilder $query): EloquentBuilder => $query->whereIn('priority', ['high', 'urgent'])),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('markCompleted')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'completed')
                    ->form([
                        Forms\Components\Textarea::make('result')->required(),
                        Forms\Components\Textarea::make('remarks'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update(array_merge($data, [
                            'status' => 'completed',
                            'completed_at' => now(),
                        ]));
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markCompleted')
                        ->label('Mark Completed')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['status' => 'completed', 'completed_at' => now()])),
                    Tables\Actions\BulkAction::make('assignAgent')
                        ->label('Assign to Agent')
                        ->icon('heroicon-o-user')
                        ->form([
                            Forms\Components\Select::make('assigned_to')
                                ->label('Agent')
                                ->relationship('assignedAgent', 'name')
                                ->required(),
                        ])
                        ->action(fn ($records, array $data) => $records->each->update(['assigned_to' => $data['assigned_to']])),
                ]),
            ])
            ->defaultSort('due_date', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        try {
            return static::getModel()::whereDate('due_date', '<=', today())->where('status', '!=', 'completed')->count();
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        try {
            $overdueCount = static::getModel()::where('due_date', '<', now())->where('status', '!=', 'completed')->count();
            return $overdueCount > 0 ? 'danger' : 'warning';
        } catch (\Exception $e) {
            return 'warning';
        }
    }
}
