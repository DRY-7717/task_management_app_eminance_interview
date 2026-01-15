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
use Filament\Resources\Resource;
use Filament\Tables;
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
                        'user',
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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTasks::route('/create'),
            'edit' => Pages\EditTasks::route('/{record}/edit'),
        ];
    }
}
