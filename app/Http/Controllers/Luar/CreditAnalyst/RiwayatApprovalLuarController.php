<?php

namespace App\Http\Controllers\Luar\CreditAnalyst;

use App\Http\Controllers\Controller;
use App\Models\Luar\NasabahLuar;
use App\Models\Luar\PengajuanNasabahLuar;
use Illuminate\Http\Request;

class RiwayatApprovalLuarController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanNasabahLuar::whereHas('approval', function ($query) {
            $query->where('role', 'ca')->whereNotNull('status'); // Hanya ambil yang sudah diproses oleh Credit Analyst (CA)
        })
            ->with([
                'nasabahLuar.user', // Ambil data nasabah & user yang terkait
                'approval' => function ($query) {
                    $query->where('role', 'ca'); // Pastikan approval yang diambil hanya untuk CA
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($pengajuan);

        // $pengajuan = NasabahLuar::whereHas('user', function ($query) {
        //     $query->where('usertype', 'marketing');
        // })
        //     ->whereHas('pengajuanLuar.approval', function ($query) {
        //         $query->where('role', 'ca'); // Sesuaikan jika ada kolom 'status'
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
        //             $query->where('role', 'ca');
        //         }
        //     ])
        //     ->get();

        return view('pengajuan-luar.ca-luar.riwayat-approval-pengajuan-luar', compact('pengajuan'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat', 'hasilSurvey')->findOrFail($id);
        $isSurvey = $pengajuan->whereHas('hasilSurvey', function ($query) use ($pengajuan) {
            $query->where('pengajuan_id', $pengajuan->first()->id);
        })
            ->exists();
        return view('pengajuan-luar.ca-luar.detail-riwayat-approval', compact('pengajuan', 'isSurvey'));
    }
}
