<?php

namespace App\Filament\Resources\CcoRecordingResource\Pages;

use App\Filament\Resources\CcoRecordingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCcoRecording extends EditRecord
{
    protected static string $resource = CcoRecordingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
