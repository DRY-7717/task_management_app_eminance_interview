<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusResource\Pages;
use App\Filament\Resources\StatusResource\RelationManagers;
use App\Models\Status;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusResource extends Resource
{
    protected static ?string $model = Status::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Status';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Status Name')
                    ->placeholder('Input status name')
                    ->unique('statuses', 'name', ignoreRecord: true)
                    ->required(),
                TextInput::make('sort_order')
                    ->label('Order')
                    ->readOnly()
                    ->default(fn() => (Status::max('sort_order') ?? 0) + 1),
                Toggle::make('is_active')
                    ->label('Active')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('is_active')
                    ->badge()
                    ->color(fn(bool $state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn(bool $state) => $state ? 'Active' : 'Inactive')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Order'),

            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
            ], FiltersLayout::AboveContent)

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Status $record) {
                        Status::where('sort_order', '>', $record->sort_order)
                            ->decrement('sort_order');
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Status deleted')
                            ->body('Status deleted successfully.')
                    ),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(callback: function ($records) {
                            Status::orderBy('sort_order')->get()
                                ->each(function ($status, $index) {
                                    $status->sort_order = $index + 1;
                                    $status->save();
                                });
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Status deleted')
                                ->body('Status deleted successfully.')
                        ),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-tag')
            ->emptyStateHeading("No statuses yet")
            ->emptyStateDescription('Once you write your first status, it will appear here.')
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order');
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
            'index' => Pages\ListStatuses::route('/'),
            'create' => Pages\CreateStatus::route('/create'),
            'edit' => Pages\EditStatus::route('/{record}/edit'),
        ];
    }
}
