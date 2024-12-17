<?php

namespace App\Filament\Widgets;

use App\Models\Agent;
use App\Models\Cco;
use App\Models\CcoRecording;
use Filament\Widgets\ChartWidget;

class CcoPhonePerMonthWidget extends ChartWidget
{
    protected static ?string $heading = 'CCO Telpon per Bulan (L1)';
    public ?string $filter = '2024';

    protected function getData(): array
    {

        $activeFilter = $this->filter;
        $agents = Agent::whereYear('created_at', $activeFilter)
            ->with('ccoRecording')
            ->get();

        $avgBobot = 5;
        $groupByMonth = $agents->groupBy(function ($agent) {
            return $agent->created_at->format('m');
        });

        $persentasePerBulan = [];
        foreach ($groupByMonth as $month => $agentsInMonth) {
            $totalRataRataSemuaSample = 0;
            $totalSampleCount = 0;

            foreach ($agentsInMonth as $agent) {
                foreach ($agent->ccoRecording as $cco) {
                    $data = collect($cco)->except(['id']);
                    $filteredData = $data->filter(function ($value, $key) {
                        return is_numeric($value);
                    });
                    $avgSample = round($filteredData->avg(), 0);
                    $totalRataRataSemuaSample += $avgSample;
                    $totalSampleCount++;
                }
            }

            if ($totalSampleCount > 0) {
                $totalRataRataSemuaSample /= $totalSampleCount;
            }

            $persentasePerBulan[$month] = round((($totalRataRataSemuaSample / $avgBobot) * 100), 1);
        }

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            array_push($monthlyData, round($persentasePerBulan[$i] ?? 0, 1));
        }

        session()->put('ccoPhone', $persentasePerBulan);
        return [
            'labels' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            'datasets' => [
                [
                    'label' => 'Persentase Telpon(L1)',
                    'data' => $monthlyData,
                ]
            ]
        ];
    }

    protected function getFilters(): ?array
    {
        $data = CcoRecording::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year')->toArray();
        return $data;
    }

    protected function getType(): string
    {
        return 'line';
    }
}
