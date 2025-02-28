<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Kehadiran';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Attendance::select('status', DB::raw('count(*) as total'))
            ->whereMonth('date', Carbon::now()->month)
            ->groupBy('status')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Kehadiran Bulan Ini',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#10B981', // hadir - green
                        '#F59E0B', // izin - yellow
                        '#3B82F6', // sakit - blue
                        '#6366F1', // cuti - indigo
                        '#EF4444', // alpha - red
                    ],
                ],
            ],
            'labels' => $data->pluck('status')->map(function ($status) {
                return ucfirst($status);
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'color' => '#fff',
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
            'height' => 300,
        ];
    }
} 