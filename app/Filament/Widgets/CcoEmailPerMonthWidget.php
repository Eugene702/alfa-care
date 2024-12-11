<?php

namespace App\Filament\Widgets;

use App\Models\Agent;
use App\Models\Cco;
use Filament\Widgets\ChartWidget;

class CcoEmailPerMonthWidget extends ChartWidget
{
    protected static ?string $heading = 'CCO Email per bulan (L1)';
    public ?string $filter = '2024';

    protected function getData(): array
    {

        $activeFilter = $this->filter;
        $agents = Agent::whereYear('created_at', $activeFilter)
            ->with('cco')
            ->get();

        $avgBobot = 11;
        $groupByMonth = $agents->groupBy(function ($agent) {
            return $agent->created_at->format('m');
        });

        $persentasePerBulan = [];
        foreach ($groupByMonth as $month => $agentsInMonth) {
            $totalRataRataSemuaSample = 0;
            $totalSampleCount = 0;

            foreach ($agentsInMonth as $agent) {
                foreach ($agent->cco as $cco) {
                    $data = collect($cco)->except(['id']);
                    $filteredData = $data->filter(function ($value, $key) {
                        return is_numeric($value);
                    });
                    $avgSample = $filteredData->avg();
                    $totalRataRataSemuaSample += $avgSample;
                    $totalSampleCount++;
                }
            }

            if ($totalSampleCount > 0) {
                $totalRataRataSemuaSample /= $totalSampleCount;
            }

            $persentasePerBulan[$month] = ($totalRataRataSemuaSample / $avgBobot) * 100;
        }

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            array_push($monthlyData, round($persentasePerBulan[$i] ?? 0, 1));
        }

        session()->put('ccoEmail', $persentasePerBulan);
        return [
            'labels' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            'datasets' => [
                [
                    'label' => 'Persentase Email(L1)',
                    'data' => $monthlyData,
                ]
            ]
        ];
    }


    protected function getFilters(): ?array
    {
        $data = Cco::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year')->toArray();
        return $data;
    }

    protected function getType(): string
    {
        return 'line';
    }
}
