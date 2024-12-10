<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;

class Report extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.report';

    protected function getHeaderActions(): array{
        return [
            Action::make('delete')
                ->requiresConfirmation()
        ];
    }

    protected function test(){
        return 'test';
    }
}
