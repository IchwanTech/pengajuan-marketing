<?php

namespace App\Http\Controllers\Luar\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Luar\NasabahLuar;
use App\Models\Luar\PengajuanNasabahLuar;
use Illuminate\Http\Request;

class RiwayatPengajuanLuarController extends Controller
{
    public function index()
    {
        // $pengajuan = NasabahLuar::whereHas('user', function ($query) {
        //     $query->where('usertype', 'marketing');
        // })
        //     ->whereHas('pengajuanLuar.approval', function ($query) {
        //         $query->where('role', 'spv'); // Sesuaikan jika ada kolom 'status'
        //     })
        //     ->with([
        //         'user',
        //         'alamatLuar',
        //         'pekerjaanLuar',
        //         'penjaminLuar',
        //         'tanggunganLuar',
        //         'pinjamanLainLuar',
        //         'kontakDarurat',
        //         'pengajuanLuar.approval' => function ($query) {
        //             $query->where('role', 'spv');
        //         }
        //     ])
        //     ->get();

        $pengajuan = PengajuanNasabahLuar::whereHas('approval', function ($query) {
            $query->where('role', 'spv')->whereNotNull('status'); // Hanya ambil yang sudah diproses oleh Credit Analyst (CA)
        })
            ->with([
                'nasabahLuar.user', // Ambil data nasabah & user yang terkait
                'approval' => function ($query) {
                    $query->where('role', 'spv'); // Pastikan approval yang diambil hanya untuk CA
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengajuan-luar.supervisor.riwayat-pengajuan-luar', compact('pengajuan'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat', 'hasilSurvey')->findOrFail($id);

        return view('pengajuan-luar.supervisor.detail-riwayat-pengajuan', compact('pengajuan'));
    }
}
