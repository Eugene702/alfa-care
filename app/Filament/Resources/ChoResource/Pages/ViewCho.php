<?php

namespace App\Filament\Resources\ChoResource\Pages;

use App\Filament\Resources\ChoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCho extends ViewRecord
{
    protected static string $resource = ChoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
