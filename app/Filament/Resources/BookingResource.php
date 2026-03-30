<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Sales Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'booking_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Booking Details')
                    ->schema([
                        Forms\Components\Select::make('opportunity_id')
                            ->relationship('opportunity', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $opportunity = \App\Models\Opportunity::with(['lead', 'properties', 'agent'])->find($state);
                                    if ($opportunity) {
                                        $set('customer_lead_id', $opportunity->lead_id);
                                        $set('employee_id', $opportunity->assigned_to);
                                        $set('agent_id', $opportunity->agent_id);
                                        
                                        $property = $opportunity->properties->first();
                                        if ($property) {
                                            $set('property_id', $property->id);
                                            $set('property_value', $property->price_min);
                                        }
                                    }
                                }
                            }),
                        Forms\Components\Select::make('property_id')
                            ->relationship('property', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('customer_lead_id')
                            ->label('Customer')
                            ->relationship('customer', 'full_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('employee_id')
                            ->label('Assigned Employee')
                            ->relationship('employee', 'name', fn ($query) => $query->employees()->active())
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('agent_id')
                            ->label('Agent (if any)')
                            ->relationship('agent', 'name', fn ($query) => $query->active())
                            ->searchable()
                            ->preload()
                            ->helperText('Commission will be calculated automatically'),
                    ])->columns(2),

                Forms\Components\Section::make('Property Value')
                    ->schema([
                        Forms\Components\TextInput::make('property_value')
                            ->label('Final Property Value')
                            ->numeric()
                            ->prefix('₹')
                            ->required()
                            ->helperText('This is the agreed final price'),
                    ]),

                Forms\Components\Section::make('Token Payment')
                    ->schema([
                        Forms\Components\TextInput::make('token_amount')
                            ->numeric()
                            ->prefix('₹'),
                        Forms\Components\DatePicker::make('token_date'),
                    ])->columns(2),

                Forms\Components\Section::make('Booking Payment')
                    ->schema([
                        Forms\Components\TextInput::make('booking_amount')
                            ->numeric()
                            ->prefix('₹')
                            ->helperText('Typically 10% of property value'),
                        Forms\Components\DatePicker::make('booking_date'),
                    ])->columns(2),

                Forms\Components\Section::make('Agreement Details')
                    ->schema([
                        Forms\Components\TextInput::make('agreement_value')
                            ->numeric()
                            ->prefix('₹'),
                        Forms\Components\DatePicker::make('agreement_date'),
                        Forms\Components\TextInput::make('agreement_number')
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('Registration Details')
                    ->schema([
                        Forms\Components\DatePicker::make('registration_date'),
                        Forms\Components\TextInput::make('registration_number')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Possession')
                    ->schema([
                        Forms\Components\DatePicker::make('possession_date'),
                    ]),

                Forms\Components\Section::make('Booking Stage')
                    ->schema([
                        Forms\Components\Select::make('booking_stage')
                            ->options([
                                'Token Received' => 'Token Received',
                                'Token Confirmed' => 'Token Confirmed',
                                'Agreement Pending' => 'Agreement Pending',
                                'Agreement Signed' => 'Agreement Signed',
                                'Payment Plan Active' => 'Payment Plan Active',
                                'Registration Pending' => 'Registration Pending',
                                'Registration Done' => 'Registration Done',
                                'Possession Pending' => 'Possession Pending',
                                'Possession Done' => 'Possession Done',
                                'Completed' => 'Completed',
                            ])
                            ->required()
                            ->default('Token Received'),
                    ]),

                Forms\Components\Section::make('Commission')
                    ->schema([
                        Forms\Components\TextInput::make('agent_commission_percentage')
                            ->label('Commission %')
                            ->numeric()
                            ->suffix('%')
                            ->disabled(),
                        Forms\Components\TextInput::make('agent_commission_amount')
                            ->label('Commission Amount')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled(),
                        Forms\Components\Select::make('commission_status')
                            ->options([
                                'Pending' => 'Pending',
                                'Calculated' => 'Calculated',
                                'Approved' => 'Approved',
                                'Paid' => 'Paid',
                            ])
                            ->default('Pending'),
                    ])->columns(3),

                Forms\Components\Section::make('Commission Payment')
                    ->schema([
                        Forms\Components\TextInput::make('commission_paid')
                            ->numeric()
                            ->prefix('₹')
                            ->default(0),
                        Forms\Components\DatePicker::make('commission_paid_date'),
                        Forms\Components\TextInput::make('payment_reference')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('invoice_generated')
                            ->label('Invoice Generated'),
                        Forms\Components\TextInput::make('invoice_number')
                            ->maxLength(255),
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
                Tables\Columns\TextColumn::make('booking_number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->property->name),
                Tables\Columns\TextColumn::make('property_value')
                    ->label('Value')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('booking_stage')
                    ->colors([
                        'danger' => 'Token Received',
                        'warning' => ['Token Confirmed', 'Agreement Pending'],
                        'primary' => ['Agreement Signed', 'Payment Plan Active'],
                        'info' => ['Registration Pending', 'Registration Done'],
                        'success' => ['Possession Done', 'Completed'],
                    ]),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Employee')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->toggleable()
                    ->default('Direct'),
                Tables\Columns\TextColumn::make('agent_commission_amount')
                    ->label('Commission')
                    ->money('INR')
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('commission_status')
                    ->colors([
                        'danger' => 'Pending',
                        'warning' => 'Calculated',
                        'info' => 'Approved',
                        'success' => 'Paid',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Booked On')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('booking_stage')
                    ->options([
                        'Token Received' => 'Token Received',
                        'Token Confirmed' => 'Token Confirmed',
                        'Agreement Signed' => 'Agreement Signed',
                        'Registration Done' => 'Registration Done',
                        'Completed' => 'Completed',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('commission_status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Paid' => 'Paid',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Employee')
                    ->multiple(),
                Tables\Filters\SelectFilter::make('agent_id')
                    ->relationship('agent', 'name')
                    ->label('Agent')
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approveCommission')
                    ->label('Approve Commission')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Booking $record) => $record->update(['commission_status' => 'Approved']))
                    ->visible(fn (Booking $record) => $record->commission_status === 'Pending' && $record->agent_id),
                Tables\Actions\Action::make('markPaid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('commission_paid')
                            ->label('Amount Paid')
                            ->numeric()
                            ->prefix('₹')
                            ->required(),
                        Forms\Components\DatePicker::make('commission_paid_date')
                            ->label('Payment Date')
                            ->default(now())
                            ->required(),
                        Forms\Components\TextInput::make('payment_reference')
                            ->label('Payment Reference/UTR')
                            ->required(),
                    ])
                    ->action(function (Booking $record, array $data) {
                        $record->update([
                            'commission_status' => 'Paid',
                            'commission_paid' => $data['commission_paid'],
                            'commission_paid_date' => $data['commission_paid_date'],
                            'payment_reference' => $data['payment_reference'],
                            'invoice_generated' => true,
                            'invoice_number' => 'INV-' . $record->booking_number,
                        ]);
                    })
                    ->visible(fn (Booking $record) => $record->commission_status === 'Approved' && $record->agent_id),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('updateStage')
                        ->label('Update Stage')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('booking_stage')
                                ->options([
                                    'Token Received' => 'Token Received',
                                    'Token Confirmed' => 'Token Confirmed',
                                    'Agreement Pending' => 'Agreement Pending',
                                    'Agreement Signed' => 'Agreement Signed',
                                    'Registration Done' => 'Registration Done',
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update(['booking_stage' => $data['booking_stage']]);
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
                Infolists\Components\Section::make('Booking Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('booking_number')->label('Booking Number'),
                        Infolists\Components\TextEntry::make('booking_stage')->badge(),
                        Infolists\Components\TextEntry::make('stage_progress')
                            ->label('Progress')
                            ->formatStateUsing(fn ($state) => $state . '%')
                            ->badge(),
                    ])->columns(3),
                
                Infolists\Components\Section::make('Parties Involved')
                    ->schema([
                        Infolists\Components\TextEntry::make('customer.full_name')->label('Customer'),
                        Infolists\Components\TextEntry::make('customer.mobile')->label('Customer Mobile'),
                        Infolists\Components\TextEntry::make('employee.name')->label('Assigned Employee'),
                        Infolists\Components\TextEntry::make('agent.name')->label('Agent')->default('Direct Sale'),
                    ])->columns(4),
                
                Infolists\Components\Section::make('Property Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('property.name')->label('Property'),
                        Infolists\Components\TextEntry::make('property.location')->label('Location'),
                        Infolists\Components\TextEntry::make('property_value')->money('INR')->label('Deal Value'),
                    ])->columns(3),
                
                Infolists\Components\Section::make('Payment Timeline')
                    ->schema([
                        Infolists\Components\TextEntry::make('token_amount')->money('INR'),
                        Infolists\Components\TextEntry::make('token_date')->date('d M Y'),
                        Infolists\Components\TextEntry::make('booking_amount')->money('INR'),
                        Infolists\Components\TextEntry::make('booking_date')->date('d M Y'),
                        Infolists\Components\TextEntry::make('agreement_date')->date('d M Y'),
                        Infolists\Components\TextEntry::make('registration_date')->date('d M Y'),
                    ])->columns(3),
                
                Infolists\Components\Section::make('Commission Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('agent_commission_percentage')
                            ->label('Commission Rate')
                            ->formatStateUsing(fn ($state) => $state ? $state . '%' : 'N/A'),
                        Infolists\Components\TextEntry::make('agent_commission_amount')
                            ->money('INR')
                            ->label('Commission Amount'),
                        Infolists\Components\TextEntry::make('commission_status')->badge(),
                        Infolists\Components\TextEntry::make('commission_paid')->money('INR'),
                        Infolists\Components\TextEntry::make('commission_pending')
                            ->money('INR')
                            ->label('Pending Amount'),
                        Infolists\Components\TextEntry::make('commission_paid_date')->date('d M Y'),
                    ])->columns(3)
                    ->visible(fn ($record) => $record->agent_id !== null),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('commission_status', 'Pending')->where('agent_id', '!=', null)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
