<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TasksResource\Pages;
use App\Filament\Resources\TasksResource\RelationManagers;
use App\Models\Tasks;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TasksResource extends Resource
{
    protected static ?string $model = Tasks::class;


    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Task';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->placeholder('Input task title')
                    ->required(),
                Select::make('status_id')
                    ->label('Status')
                    ->relationship(
                        'status',
                        'name',
                        fn(Builder $query) => $query->where('is_active', 1),
                    )
                    ->required(),
                Select::make('severity_id')
                    ->label('Severity')
                    ->relationship(
                        'severity',
                        'name',
                    )
                    ->required(),
                Select::make('developer_id')
                    ->label('Developer')
                    ->relationship(
                        'user',
                        'name',
                        fn(Builder $query) => $query->where('role', 'developer')
                    )
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Start date')
                    ->required(),
                DatePicker::make('due_date')
                    ->label(label: 'Due date')
                    ->required(),
                DatePicker::make('finish_date')
                    ->label('Finish date')
                    ->nullable(),
                Select::make('created_by')
                    ->label('Created By')
                    ->relationship(
                        'createdby',
                        'name',
                        fn(Builder $query) => $query->where('role', 'admin')
                    )
                    ->required(),
                Textarea::make(name: 'description')
                    ->label('Description')
                    ->required()
                    ->columnSpan([
                        'default' => 2
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(name: 'title')
                    ->label('Title')
                    ->searchable(),
                TextColumn::make(name: 'status.name')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Waiting' => 'gray',
                        'In Progress' => 'warning',
                        'Pending' => 'primary',
                        'Completed' => 'success',
                        'Closed' => 'danger',
                    })
                    ->searchable(),
                TextColumn::make(name: 'severity.name')
                    ->label('Severity')
                    ->badge()
                    ->color(function ($record) {
                        return Color::hex($record->severity->color);
                    })
                    ->searchable(),
                TextColumn::make(name: 'user.name')
                    ->label('Assignee')
                    ->searchable(),
                TextColumn::make(name: 'start_date')
                    ->label('Start date')
                    ->searchable(),
                TextColumn::make(name: 'due_date')
                    ->label('Due date')
                    ->searchable(),
                TextColumn::make(name: 'finish_date')
                    ->label('Finis date')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make(name: 'createdby.name')
                    ->label('Created By')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->relationship('status', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                SelectFilter::make('severity')
                    ->label('Severity')
                    ->relationship('severity', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
            ], FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Task deleted')
                            ->body('Task deleted successfully.')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Task deleted')
                                ->body('Task deleted successfully.')
                        ),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->emptyStateHeading("No tasks yet")
            ->emptyStateDescription('Once you write your first task, it will appear here.');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->role != 'admin') {
            $query->where('developer_id', auth()->user()->id);
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTasks::route('/create'),
            'edit' => Pages\EditTasks::route('/{record}/edit'),
        ];
    }
}
