<?php

namespace App\Filament\Resources\CcoRecordingResource\Pages;

use App\Filament\Resources\CcoRecordingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCcoRecording extends ViewRecord
{
    protected static string $resource = CcoRecordingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
