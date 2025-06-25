<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class RiwayatApprovalSpvController extends Controller
{
    public function index()
    {
        $riwayat = Nasabah::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuan.approval', function ($query) {
                $query->where('role', 'spv'); // Sesuaikan jika ada kolom 'status'
            })
            ->with([
                'user',
                'alamat',
                'jaminan',
                'keluarga',
                'kerabat',
                'pekerjaan',
                'pengajuan' => function ($query) {
                    $query->with(['approval' => function ($query) {
                        $query->where('role', 'spv');
                    }]);
                }
            ])
            ->get();
            
            // Mengurutkan berdasarkan tanggal approval (misalnya 'created_at' pada approval)
            $riwayat = $riwayat->sortBy(function ($nasabah) {
                return optional($nasabah->pengajuan->approval->first())->created_at; // Pastikan field sesuai
            });

        return view('supervisor.riwayat-approval', compact('riwayat'));
    }

    public function show($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('supervisor.detail-riwayat', compact('nasabah'));
    }
}
