<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use App\Imports\LeadsImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Lead')
                ->icon('heroicon-o-plus'),
            
            Actions\Action::make('import')
                ->label('Import Leads')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('Upload CSV/Excel File')
                        ->acceptedFileTypes([
                            'text/csv',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->maxSize(5120)
                        ->required()
                        ->helperText('Upload CSV or Excel file with leads data'),
                    
                    Forms\Components\Placeholder::make('template_info')
                        ->label('Need a template?')
                        ->content(fn () => view('filament.components.download-template-link')),
                ])
                ->action(function (array $data) {
                    try {
                        $file = storage_path('app/public/' . $data['file']);
                        
                        Excel::import(new LeadsImport, $file);
                        
                        Notification::make()
                            ->title('Leads imported successfully!')
                            ->success()
                            ->send();
                        
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import failed!')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->modalWidth('lg'),
            
            Actions\Action::make('download_template')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(fn () => asset('storage/template/lead-import-template.csv'))
                ->openUrlInNewTab(),
        ];
    }
}
