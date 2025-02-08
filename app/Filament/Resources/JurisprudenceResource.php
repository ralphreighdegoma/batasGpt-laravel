<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurisprudenceResource\Pages;
use App\Filament\Resources\JurisprudenceResource\RelationManagers;
use App\Models\Jurisprudence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JurisprudenceResource extends Resource
{
    protected static ?string $model = Jurisprudence::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reference_number')
                    ->required(),
                Forms\Components\DatePicker::make('decision_date')
                    ->required(),
                Forms\Components\TextInput::make('court')
                    ->required()
                    ->maxLength(255),
                //rich text editor
                Forms\Components\RichEditor::make('content')
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
            ]);
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
            'index' => Pages\ListJurisprudences::route('/'),
            'create' => Pages\CreateJurisprudence::route('/create'),
            'edit' => Pages\EditJurisprudence::route('/{record}/edit'),
        ];
    }
}
