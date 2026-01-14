<?php

namespace App\Filament\Resources\SeverityResource\Pages;

use App\Filament\Resources\SeverityResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSeverity extends CreateRecord
{
    protected static string $resource = SeverityResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Severity created')
            ->body('Severity added successfully.');
    }
}
