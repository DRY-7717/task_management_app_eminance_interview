<?php

namespace App\Filament\Resources\StatusResource\Pages;

use App\Filament\Resources\StatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatuses extends ListRecords
{
    protected static string $resource = StatusResource::class;
    protected static ?string $title = 'Status List';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label("Add Status")
                ->icon('heroicon-o-plus'),
        ];
    }
}
