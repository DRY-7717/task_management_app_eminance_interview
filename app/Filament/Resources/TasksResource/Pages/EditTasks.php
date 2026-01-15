<?php

namespace App\Filament\Resources\TasksResource\Pages;

use App\Filament\Resources\TasksResource;
use App\Mail\TaskCompleted;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditTasks extends EditRecord
{
    protected static string $resource = TasksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Task updated')
            ->body('Task updated successfully.');
    }

    protected function afterSave(): void
    {
        if (!auth()->user()->is_admin && $this->record->wasChanged('status_id') && $this->record->status?->name === 'Completed') {

            Mail::to(env('MAIL_TO'))->send(
                new TaskCompleted(
                    task: $this->record,
                    changedBy: auth()->user()
                )
            );
        }
    }
}
