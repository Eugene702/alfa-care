<?php

namespace App\Filament\Resources\ChoResource\Pages;

use App\Filament\Resources\ChoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCho extends EditRecord
{
    protected static string $resource = ChoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
