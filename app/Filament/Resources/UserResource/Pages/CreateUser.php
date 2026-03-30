<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-generate employee code if not provided
        if (empty($data['employee_code'])) {
            $lastEmployee = \App\Models\User::orderBy('id', 'desc')->first();
            $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;
            $data['employee_code'] = 'EMP-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        }
        
        return $data;
    }
}
