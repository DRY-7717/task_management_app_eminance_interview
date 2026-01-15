<?php

namespace App\Filament\Resources\TasksResource\Pages;

use App\Filament\Resources\TasksResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTasks extends CreateRecord
{
    protected static string $resource = TasksResource::class;
    protected static ?string $title = 'Create Task';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Task created')
            ->body('Task added successfully.');
    }
}
