<?php

namespace App\Http\Controllers\CreditAnalyst;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class RiwayatApprovalCAController extends Controller
{
    public function index()
    {
        $riwayat = Nasabah::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuan.approval', function ($query) {
                $query->where('role', 'ca'); // Sesuaikan jika ada kolom 'status'
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
                        $query->where('role', 'ca');
                    }]);
                }
            ])
            ->get()
            ->sortBy('pengajuan.created_at');

        return view('creditAnalyst.riwayat-approval-ca', compact('riwayat'));
    }

    public function show($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('creditAnalyst.detail-riwayat', compact('nasabah'));
    }
}
