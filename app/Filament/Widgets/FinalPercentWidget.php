<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class FinalPercentWidget extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Persentase Akhir';

    protected function getData(): array
    {
        $percentCcoEmail = session()->get('ccoEmail', []);
        $percentCcoPhone = session()->get('ccoPhone', []);
        $percentCho = session()->get('cho', []);

        $totalCcoEmail = array_sum($percentCcoEmail);
        $totalCcoPhone = array_sum($percentCcoPhone);
        $totalCho = array_sum($percentCho);
        $total = $totalCcoEmail + $totalCcoPhone + $totalCho;
        $persentasePieData = [
            'CCO Email' => $total > 0 ? ($totalCcoEmail / $total) * 100 : 0,
            'CCO Telepon' => $total > 0 ? ($totalCcoPhone / $total) * 100 : 0,
            'CHO (L2)' => $total > 0 ? ($totalCho / $total) * 100 : 0,
        ];

        return [
            'labels' => array_keys($persentasePieData),
            'datasets' => [
                [
                    'data' => array_values($persentasePieData),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'],
                    'hoverBackgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
