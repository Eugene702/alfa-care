<?php

namespace App\Filament\Resources\CcoResource\Pages;

use App\Filament\Resources\CcoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCco extends EditRecord
{
    protected static string $resource = CcoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
