<?php

namespace App\Filament\Resources\JurisprudenceResource\Pages;

use App\Filament\Resources\JurisprudenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJurisprudence extends EditRecord
{
    protected static string $resource = JurisprudenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
