<?php

namespace App\Imports;

use App\Models\Voucher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class VouchersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Voucher([
            'nama'         => $row['nama'] ?? null,
            'nik'          => $row['nik'] ?? null,
            'kode_voucher' => $row['kode_voucher'] ?? null,
            'kadaluarsa'   => $this->parseTanggal($row['kadaluarsa'] ?? null),
            'type'         => $row['type'] ?? null,
            'saldo'        => $row['saldo'] ?? null,
        ]);
    }

    private function parseTanggal($tanggal)
    {
        Log::info("Tanggal dari Excel: " . $tanggal);

        // Jika formatnya angka (serial number Excel)
        if (is_numeric($tanggal)) {
            return Carbon::createFromFormat('Y-m-d', gmdate('Y-m-d', ($tanggal - 25569) * 86400))->format('Y-m-d');
        }

        try {
            // Tangkap format tanggal apapun dan ubah menjadi Y-m-d
            return Carbon::parse($tanggal)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // Jika gagal parsing, simpan NULL
        }
    }
}
