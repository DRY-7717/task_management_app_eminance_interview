<?php

namespace App\Filament\Resources\TasksResource\Pages;

use App\Filament\Resources\TasksResource;
use App\Filament\Resources\TasksResource\Widgets\CommentsWidget;
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

            Action::make('back_to_list')
                ->color('info')
                ->url(fn(): string => route('filament.admin.resources.tasks.index')),

            EditAction::make('edit_status')
                ->label('Update Status')
                ->record($this->getRecord())
                ->visible(fn() => !auth()->user()->is_admin),


            EditAction::make()
                ->visible(fn() => auth()->user()->is_admin),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            CommentsWidget::class,
        ];
    }
}
