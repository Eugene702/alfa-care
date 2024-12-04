<?php

namespace App\Filament\Resources\CcoResource\Pages;

use App\Filament\Resources\CcoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCcos extends ListRecords
{
    protected static string $resource = CcoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
