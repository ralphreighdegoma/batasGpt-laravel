<?php

namespace App\Filament\Resources\JurisprudenceResource\Pages;

use App\Filament\Resources\JurisprudenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJurisprudences extends ListRecords
{
    protected static string $resource = JurisprudenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
