<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeverityResource\Pages;
use App\Filament\Resources\SeverityResource\RelationManagers;
use App\Models\Severity;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeverityResource extends Resource
{
    protected static ?string $model = Severity::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Severity';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Severity Name')
                    ->placeholder('Input severity name')
                    ->unique('severities', 'name', ignoreRecord: true)
                    ->required(),
                TextInput::make('color')
                    ->label('Color')
                    ->placeholder('input the hexadecimal color. Ex: #ffffff')
                    ->nullable(),
                TextInput::make('sort_order')
                    ->label('Order')
                    ->readOnly()
                    ->default(fn() => (Severity::max('sort_order') ?? 0) + 1),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('color')
                    ->label('Color')
                    ->placeholder('-')
                    ->badge()
                    ->color(function ($record) {
                        return Color::hex($record->color);
                    })
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Order')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Severity $record) {
                        Severity::where('sort_order', '>', $record->sort_order)
                            ->decrement('sort_order');
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Severity deleted')
                            ->body('Severity deleted successfully.')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(callback: function ($records) {
                            Severity::orderBy('sort_order')->get()
                                ->each(function ($status, $index) {
                                    $status->sort_order = $index + 1;
                                    $status->save();
                                });
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Severity deleted')
                                ->body('Severity deleted successfully.')
                        ),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-chart-bar')
            ->emptyStateHeading("No severities yet")
            ->emptyStateDescription('Once you write your first severity, it will appear here.')
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
            'index' => Pages\ListSeverities::route('/'),
            'create' => Pages\CreateSeverity::route('/create'),
            'edit' => Pages\EditSeverity::route('/{record}/edit'),
        ];
    }
}
