<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $today = Carbon::today();

        return [
            Stat::make('Total Pegawai', User::count())
                ->description('Total pegawai terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Hadir Hari Ini', Attendance::whereDate('date', $today)->where('status', 'hadir')->count())
                ->description('Pegawai yang hadir hari ini')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary')
                ->chart([2, 4, 6, 8, 7, 6, 4, 5]),

            Stat::make('Tidak Hadir', Attendance::whereDate('date', $today)->whereIn('status', ['izin', 'sakit', 'cuti', 'alpha'])->count())
                ->description('Pegawai tidak hadir hari ini')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->chart([3, 2, 1, 2, 3, 2, 1, 0]),
        ];
    }
} 