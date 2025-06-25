<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NomorKontrakService
{
    public function generate(string $type, ?string $perusahaan = null): array
    {
        $now = Carbon::now();
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        if (in_array($type, ['Internal', 'Internal BPJS']) && $perusahaan) {
            $kelompok = strtolower("internal_{$perusahaan}");
        } elseif (in_array($type, ['Borongan', 'Borongan BPJS'])) {
            $kelompok = 'borongan';
        } elseif (in_array($type, ['Kedinasan', 'Kedinasan & Agunan', 'Kedinasan & Taspen'])) {
            $kelompok = 'kedinasan';
        } elseif (in_array($type, ['UMKM', 'UMKM Agunan'])) {
            $kelompok = 'umkm';
        } elseif (in_array($type, ['PT SF', 'PT SF Agunan', 'PT SF BPJS', 'PT SF BPJS Agunan'])) {
            $kelompok = 'pt_sf';
        } else {
            $kelompok = 'pt_luar';
        }

        $bulan = $now->month;
        $tahun = $now->year;

        $lastUrut = DB::table('surat_kontraks')
            ->where('kelompok', $kelompok)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->max('nomor_urut');

        $tahunKontrak = $now->format('y');
        $nextUrut = $lastUrut ? $lastUrut + 1 : 1;
        $nomorFormatted = str_pad($nextUrut, 3, '0', STR_PAD_LEFT);
        $nomorKontrak = "{$nomorFormatted}/CG/{$bulanRomawi[$bulan]}/{$tahunKontrak}";

        return [
            'nomor_kontrak' => $nomorKontrak,
            'nomor_urut' => $nextUrut,
            'kelompok' => $kelompok,
        ];
    }
}
