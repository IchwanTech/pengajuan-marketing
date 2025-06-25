<?php

namespace App\Imports;

use App\Models\DataCicilan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CicilanImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        Log::info($row);

        if (empty($row['id_pinjam'])) {
            throw new \Exception("ID Pinjam tidak boleh kosong");
        }

        return new DataCicilan([
            'id_pinjam'               => $row['id_pinjam'],
            'nama_konsumen'           => $row['nama_konsumen'] ?? null,
            'divisi'                  => $row['divisi'] ?? 'Bukan Borongan',
            'tgl_pencairan'           => $this->parseTanggal($row['tanggal_pencairan'] ?? null),
            'pokok_pinjaman'          => $row['pokok_pinjaman'] ?? null,
            'jumlah_tenor_seharusnya' => $row['jumlah_tenor_seharusnya'] ?? null,
            'cicilan_perbulan'        => $row['cicilan_per_bulan'] ?? null,
            'pinjaman_ke'             => $row['pinjaman_ke'] ?? null,
            'sisa_tenor'              => $row['sisa_tenor'] ?? null,
            'sisa_pinjaman'           => $row['sisa_total_pinjaman'] ?? null,
        ]);
    }

    private function parseTanggal($tgl_pencairan)
    {
        Log::info("Tanggal dari Excel: " . $tgl_pencairan);

        // Jika formatnya angka (serial number Excel)
        if (is_numeric($tgl_pencairan)) {
            return Carbon::createFromFormat('Y-m-d', gmdate('Y-m-d', ($tgl_pencairan - 25569) * 86400))->format('Y-m-d');
        }

        try {
            // Tangkap format tanggal apapun dan ubah menjadi Y-m-d
            return Carbon::parse($tgl_pencairan)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // Jika gagal parsing, simpan NULL
        }
    }
}
