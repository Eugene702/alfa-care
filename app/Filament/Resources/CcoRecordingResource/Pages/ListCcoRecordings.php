<?php

namespace App\Filament\Resources\CcoRecordingResource\Pages;

use App\Filament\Resources\CcoRecordingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCcoRecordings extends ListRecords
{
    protected static string $resource = CcoRecordingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
