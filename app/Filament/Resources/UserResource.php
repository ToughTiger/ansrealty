<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Employees';
    protected static ?string $modelLabel = 'Employee';
    protected static ?string $pluralModelLabel = 'Employees';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Employee Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('mobile')
                            ->tel()
                            ->maxLength(15),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->helperText('Leave blank to keep current password'),
                    ])->columns(2),

                Forms\Components\Section::make('Role & Hierarchy')
                    ->schema([
                        Forms\Components\Select::make('user_type')
                            ->label('Role')
                            ->options([
                                'Admin' => 'Admin',
                                'Manager' => 'Manager',
                                'Employee' => 'Employee',
                                'Telecaller' => 'Telecaller',
                            ])
                            ->required()
                            ->default('Employee'),
                        Forms\Components\Select::make('reports_to')
                            ->label('Reports To')
                            ->relationship('manager', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Select the manager this employee reports to'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                            ])
                            ->required()
                            ->default('Active'),
                    ])->columns(3),

                Forms\Components\Section::make('Employment Details')
                    ->schema([
                        Forms\Components\TextInput::make('employee_code')
                            ->unique(ignoreRecord: true)
                            ->helperText('Leave blank for auto-generation'),
                        Forms\Components\DatePicker::make('joining_date')
                            ->default(now()),
                        Forms\Components\TextInput::make('target_monthly')
                            ->label('Monthly Sales Target')
                            ->numeric()
                            ->prefix('₹')
                            ->helperText('For sales employees only'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_code')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('user_type')
                    ->label('Role')
                    ->colors([
                        'danger' => 'Admin',
                        'warning' => 'Manager',
                        'success' => 'Employee',
                        'primary' => 'Telecaller',
                    ]),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Reports To')
                    ->searchable()
                    ->default('—'),
                Tables\Columns\TextColumn::make('assignedLeads_count')
                    ->label('Leads')
                    ->counts('assignedLeads')
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedAgents_count')
                    ->label('Agents')
                    ->counts('assignedAgents')
                    ->sortable(),
                Tables\Columns\TextColumn::make('target_monthly')
                    ->label('Target')
                    ->money('INR')
                    ->toggleable()
                    ->default('—'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'Active',
                        'danger' => 'Inactive',
                    ]),
                Tables\Columns\TextColumn::make('joining_date')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_type')
                    ->label('Role')
                    ->options([
                        'Admin' => 'Admin',
                        'Manager' => 'Manager',
                        'Employee' => 'Employee',
                        'Telecaller' => 'Telecaller',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),
                Tables\Filters\SelectFilter::make('reports_to')
                    ->relationship('manager', 'name')
                    ->label('Reports To')
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('viewAgents')
                    ->label('View Agents')
                    ->icon('heroicon-o-users')
                    ->url(fn (User $record) => route('filament.admin.resources.agents.index', [
                        'tableFilters' => ['assigned_employee_id' => ['values' => [$record->id]]],
                    ]))
                    ->visible(fn (User $record) => $record->assignedAgents_count > 0),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'Active'])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'Inactive'])),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'Active')->employees()->count();
    }
}
