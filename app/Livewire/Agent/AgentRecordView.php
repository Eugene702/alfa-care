<?php

namespace App\Livewire\Agent;

use App\Models\Agent;
use Livewire\Component;

class AgentRecordView extends Component
{
    public $agent;
    public $filter;

    public $ccoEmail;
    public $ccoPhone;
    public $cho;

    private $ccoData;
    private $choData;

    public $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];


    private function calculateCcoEmail()
    {
        $avgBobot = 11;
        $groupByMonth = $this->ccoData->groupBy(function ($agent) {
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
                    $avgSample = round  ($filteredData->avg());
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
        return $monthlyData;
    }

    private function calculateCcoPhone()
    {
        $avgBobot = 11;
        $groupByMonth = $this->ccoData->groupBy(function ($agent) {
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
                    $avgSample = round  ($filteredData->avg());
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
        return $monthlyData;
    }

    private function calculateCho()
    {
        $avgBobot = 10;
        $groupByMonth = $this->choData->groupBy(function ($agent) {
            return $agent->created_at->format('m');
        });

        $persentasePerBulan = [];
        foreach ($groupByMonth as $month => $agentsInMonth) {
            $totalRataRataSemuaSample = 0;
            $totalSampleCount = 0;

            foreach ($agentsInMonth as $agent) {
                foreach ($agent->cho as $cco) {
                    $data = collect($cco)->except(['id']);
                    $filteredData = $data->filter(function ($value, $key) {
                        return is_numeric($value);
                    });
                    $avgSample = round  ($filteredData->avg());
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
        return $monthlyData;
    }

    public function render()
    {
        if ($this->agent->position == "L1") {
            $this->ccoData = Agent::where("id", $this->agent->id)
                ->with(['cco', 'ccoRecording'])
                ->whereYear('created_at', date('Y'))
                ->get();

            $this->ccoEmail = $this->calculateCcoEmail();
            $this->ccoPhone = $this->calculateCcoPhone();
        } else {
            $this->choData = Agent::where("id", $this->agent->id)
                ->with('cho')
                ->whereYear('created_at', date('Y'))
                ->get();

            $this->cho = $this->calculateCho();
        }

        return view('livewire.agent.agent-record-view');
    }
}
