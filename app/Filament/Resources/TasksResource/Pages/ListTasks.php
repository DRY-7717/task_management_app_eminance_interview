<?php

namespace App\Filament\Resources\TasksResource\Pages;

use App\Filament\Resources\TasksResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTasks extends ListRecords
{
    protected static string $resource = TasksResource::class;

    protected static ?string $title = 'Task List';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label("Add Task")
                ->icon('heroicon-o-plus'),
        ];
    }
}
