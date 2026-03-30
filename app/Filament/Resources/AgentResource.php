<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentResource\Pages;
use App\Models\Agent;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Agent Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('agent_type')
                            ->options([
                                'Internal' => 'Internal Employee',
                                'External' => 'External Agent',
                            ])
                            ->required()
                            ->default('External')
                            ->live(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('company_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('mobile')
                            ->tel()
                            ->required()
                            ->maxLength(15),
                        Forms\Components\TextInput::make('alternate_mobile')
                            ->tel()
                            ->maxLength(15),
                    ])->columns(2),

                Forms\Components\Section::make('KYC Documents')
                    ->schema([
                        Forms\Components\TextInput::make('pan_number')
                            ->label('PAN Number')
                            ->maxLength(10)
                            ->placeholder('ABCDE1234F'),
                        Forms\Components\TextInput::make('aadhar_number')
                            ->label('Aadhar Number')
                            ->maxLength(12)
                            ->placeholder('123456789012'),
                        Forms\Components\TextInput::make('rera_number')
                            ->label('RERA Number')
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('Address')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('state')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('pincode')
                            ->maxLength(10),
                    ])->columns(3),

                Forms\Components\Section::make('Bank Details')
                    ->schema([
                        Forms\Components\TextInput::make('bank_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('account_number')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('ifsc_code')
                            ->label('IFSC Code')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('account_holder_name')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Commission Structure')
                    ->schema([
                        Forms\Components\Select::make('commission_type')
                            ->options([
                                'Percentage' => 'Percentage of Deal Value',
                                'Fixed' => 'Fixed Amount per Deal',
                            ])
                            ->required()
                            ->default('Percentage')
                            ->live(),
                        Forms\Components\TextInput::make('commission_percentage')
                            ->numeric()
                            ->suffix('%')
                            ->default(1.00)
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.1)
                            ->visible(fn (Forms\Get $get) => $get('commission_type') === 'Percentage'),
                        Forms\Components\TextInput::make('fixed_commission')
                            ->numeric()
                            ->prefix('₹')
                            ->visible(fn (Forms\Get $get) => $get('commission_type') === 'Fixed'),
                    ])->columns(3),

                Forms\Components\Section::make('Assignment & Status')
                    ->schema([
                        Forms\Components\Select::make('assigned_employee_id')
                            ->label('Assigned Employee (Relationship Manager)')
                            ->relationship('assignedEmployee', 'name', fn ($query) => $query->employees()->active())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('This employee will manage this agent'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                                'Suspended' => 'Suspended',
                            ])
                            ->required()
                            ->default('Active'),
                        Forms\Components\DatePicker::make('joining_date')
                            ->default(now()),
                    ])->columns(3),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('agent_code')
                    ->label('Agent Code')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('agent_type')
                    ->colors([
                        'success' => 'Internal',
                        'primary' => 'External',
                    ]),
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('assignedEmployee.name')
                    ->label('Assigned To')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('commission_percentage')
                    ->label('Commission')
                    ->formatStateUsing(fn ($record) => 
                        $record->commission_type === 'Percentage' 
                            ? $record->commission_percentage . '%'
                            : '₹' . number_format($record->fixed_commission, 0)
                    ),
                Tables\Columns\TextColumn::make('total_deals')
                    ->label('Deals')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_commission_earned')
                    ->label('Earned')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'Active',
                        'warning' => 'Inactive',
                        'danger' => 'Suspended',
                    ]),
                Tables\Columns\TextColumn::make('joining_date')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('agent_type')
                    ->options([
                        'Internal' => 'Internal',
                        'External' => 'External',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Suspended' => 'Suspended',
                    ]),
                Tables\Filters\SelectFilter::make('assigned_employee_id')
                    ->relationship('assignedEmployee', 'name')
                    ->label('Assigned Employee')
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('suspend')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Agent $record) => $record->update(['status' => 'Suspended']))
                    ->visible(fn (Agent $record) => $record->status !== 'Suspended'),
                Tables\Actions\Action::make('activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Agent $record) => $record->update(['status' => 'Active']))
                    ->visible(fn (Agent $record) => $record->status !== 'Active'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('assignEmployee')
                        ->label('Assign to Employee')
                        ->icon('heroicon-o-user-plus')
                        ->form([
                            Forms\Components\Select::make('assigned_employee_id')
                                ->label('Employee')
                                ->relationship('assignedEmployee', 'name', fn ($query) => $query->employees()->active())
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update(['assigned_employee_id' => $data['assigned_employee_id']]);
                            }
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Agent Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('agent_code')->label('Agent Code'),
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('agent_type')->badge(),
                        Infolists\Components\TextEntry::make('company_name'),
                        Infolists\Components\TextEntry::make('email')->copyable(),
                        Infolists\Components\TextEntry::make('mobile')->copyable(),
                    ])->columns(3),
                
                Infolists\Components\Section::make('Performance')
                    ->schema([
                        Infolists\Components\TextEntry::make('total_deals')->label('Total Deals Closed'),
                        Infolists\Components\TextEntry::make('active_deals')->label('Active Deals'),
                        Infolists\Components\TextEntry::make('total_commission_earned')
                            ->money('INR')
                            ->label('Total Commission Earned'),
                        Infolists\Components\TextEntry::make('pending_commission')
                            ->money('INR')
                            ->label('Pending Commission'),
                    ])->columns(4),
                
                Infolists\Components\Section::make('Assignment')
                    ->schema([
                        Infolists\Components\TextEntry::make('assignedEmployee.name')->label('Assigned Employee'),
                        Infolists\Components\TextEntry::make('assignedEmployee.mobile')->label('Employee Mobile'),
                        Infolists\Components\TextEntry::make('status')->badge(),
                        Infolists\Components\TextEntry::make('joining_date')->date('d M Y'),
                    ])->columns(4),
            ]);
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
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'view' => Pages\ViewAgent::route('/{record}'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'Active')->count();
    }
}
