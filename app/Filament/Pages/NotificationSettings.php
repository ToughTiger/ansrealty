<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class NotificationSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static string $view = 'filament.pages.notification-settings';
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?string $navigationLabel = 'Notification Settings';
    
    protected static ?int $navigationSort = 99;

    public static function canAccess(): bool
    {
        return true;
    }
}
