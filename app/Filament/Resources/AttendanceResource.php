<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Manajemen Absensi';

    protected static ?string $navigationLabel = 'Data Absensi';

    protected static ?string $modelLabel = 'Absensi';

    protected static ?string $pluralModelLabel = 'Daftar Absensi';

    protected static ?string $recordTitleAttribute = 'date';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('date', Carbon::today())->count() . ' Hari Ini';
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Absensi')
                    ->description('Masukkan informasi absensi pegawai.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Nama Pegawai')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Absensi')
                            ->required()
                            ->default(now())
                            ->maxDate(now())
                            ->format('d/m/Y'),

                        Forms\Components\Select::make('status')
                            ->label('Status Kehadiran')
                            ->options([
                                'hadir' => 'Hadir',
                                'izin' => 'Izin',
                                'sakit' => 'Sakit',
                                'cuti' => 'Cuti',
                                'alpha' => 'Tanpa Keterangan',
                            ])
                            ->default('hadir')
                            ->required(),

                        Forms\Components\TimePicker::make('check_in')
                            ->label('Jam Masuk')
                            ->seconds(false)
                            ->format('H:i')
                            ->hint('Format 24 jam'),

                        Forms\Components\TimePicker::make('check_out')
                            ->label('Jam Keluar')
                            ->seconds(false)
                            ->format('H:i')
                            ->hint('Format 24 jam'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan / Keterangan')
                            ->placeholder('Masukkan catatan atau keterangan tambahan')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in')
                    ->label('Jam Masuk')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out')
                    ->label('Jam Keluar')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'hadir',
                        'warning' => 'izin',
                        'info' => 'sakit',
                        'primary' => 'cuti',
                        'danger' => 'alpha',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'cuti' => 'Cuti',
                        'alpha' => 'Tanpa Keterangan',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Keterangan')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->label('Filter Pegawai')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->indicator('Pegawai'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'cuti' => 'Cuti',
                        'alpha' => 'Tanpa Keterangan',
                    ])
                    ->indicator('Status'),
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal')
                            ->format('d/m/Y'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal')
                            ->format('d/m/Y'),
                    ])
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['from'] && ! $data['until']) {
                            return null;
                        }
                        
                        if ($data['from'] && ! $data['until']) {
                            return 'Dari tanggal ' . Carbon::parse($data['from'])->format('d/m/Y');
                        }
                        
                        if (! $data['from'] && $data['until']) {
                            return 'Sampai tanggal ' . Carbon::parse($data['until'])->format('d/m/Y');
                        }
                        
                        return 'Dari ' . Carbon::parse($data['from'])->format('d/m/Y') . ' s/d ' . Carbon::parse($data['until'])->format('d/m/Y');
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-calendar')
            ->emptyStateHeading('Belum ada data absensi')
            ->emptyStateDescription('Mulai tambahkan data absensi dengan mengklik tombol di atas.');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
