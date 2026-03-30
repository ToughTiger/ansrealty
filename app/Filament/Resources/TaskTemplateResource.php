<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskTemplateResource\Pages;
use App\Models\TaskTemplate;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskTemplateResource extends Resource
{
    protected static ?string $model = TaskTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Task Templates';

    protected static ?int $navigationSort = 50;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Template Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpan(2),

                        Forms\Components\Select::make('task_type')
                            ->required()
                            ->options([
                                'Call' => 'Call',
                                'Meeting' => 'Meeting',
                                'Site Visit' => 'Site Visit',
                                'Follow-up' => 'Follow-up',
                                'Email' => 'Email',
                                'WhatsApp' => 'WhatsApp',
                                'Document' => 'Document',
                                'Other' => 'Other',
                            ]),

                        Forms\Components\Select::make('priority')
                            ->required()
                            ->options([
                                'High' => 'High',
                                'Medium' => 'Medium',
                                'Low' => 'Low',
                            ])
                            ->default('Medium'),

                        Forms\Components\TextInput::make('default_duration_hours')
                            ->label('Default Duration (Hours)')
                            ->numeric()
                            ->default(24)
                            ->required()
                            ->minValue(1)
                            ->maxValue(720)
                            ->suffix('hours'),

                        Forms\Components\Select::make('category')
                            ->options([
                                'Lead Follow-up' => 'Lead Follow-up',
                                'Site Visit' => 'Site Visit',
                                'Opportunity' => 'Opportunity',
                                'Booking' => 'Booking',
                                'Documentation' => 'Documentation',
                                'Payment' => 'Payment',
                                'Other' => 'Other',
                            ]),

                        Forms\Components\Select::make('default_assigned_to')
                            ->label('Default Assigned To')
                            ->options(User::pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Checklist Items (Optional)')
                    ->schema([
                        Forms\Components\Repeater::make('checklist_items')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->label('Checklist Item')
                                    ->required(),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Add Checklist Item')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('task_type')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'danger' => 'High',
                        'warning' => 'Medium',
                        'success' => 'Low',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('default_duration_hours')
                    ->label('Duration')
                    ->suffix(' hrs')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('defaultAssignedUser.name')
                    ->label('Default Assignee')
                    ->badge()
                    ->color('gray')
                    ->default('Not Set'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('task_type')
                    ->options([
                        'Call' => 'Call',
                        'Meeting' => 'Meeting',
                        'Site Visit' => 'Site Visit',
                        'Follow-up' => 'Follow-up',
                        'Email' => 'Email',
                        'WhatsApp' => 'WhatsApp',
                        'Document' => 'Document',
                        'Other' => 'Other',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('use_template')
                    ->label('Create Task')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->url(fn (TaskTemplate $record) => route('filament.admin.resources.tasks.create', [
                        'template' => $record->id
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskTemplates::route('/'),
            'create' => Pages\CreateTaskTemplate::route('/create'),
            'edit' => Pages\EditTaskTemplate::route('/{record}/edit'),
        ];
    }
}
