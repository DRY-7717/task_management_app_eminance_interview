<?php

namespace App\Filament\Resources\TasksResource\Pages;

use App\Filament\Resources\TasksResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTasks extends ViewRecord
{
    protected static string $resource = TasksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
            EditAction::make('edit_status')
                ->label('Update Status')
                ->record($this->getRecord())

                ->visible(fn() => !auth()->user()->is_admin),

            
            EditAction::make()
                ->visible(fn() => auth()->user()->is_admin),
        ];
    }
}
