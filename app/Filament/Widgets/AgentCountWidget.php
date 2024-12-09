<?php

namespace App\Filament\Widgets;

use App\Models\Agent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AgentCountWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalAgents = Agent::count();
        return [
            Stat::make('Total Agents', $totalAgents)
        ];
    }
}
