<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $filterTime = $request->input('filter_time');
        $month = $request->input('month');
        $year = $request->input('year');

        $riwayat = Nasabah::select('id', 'nama_lengkap', 'marketing_id')
            ->whereHas('user', fn($q) => $q->where('usertype', 'marketing'))
            ->whereHas('pengajuan', function ($query) use ($filterTime, $month, $year) {
                // $query->whereIn('status', ['aproved head', 'rejected head', 'approved banding head', 'rejected banding head']);

                // Filter berdasarkan waktu
                if ($filterTime == 'today') {
                    $query->whereDate('created_at', now());
                } elseif ($filterTime == 'this_month') {
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                } elseif ($filterTime == 'this_year') {
                    $query->whereYear('created_at', now()->year);
                }

                // Filter bulan tertentu
                if ($month) $query->whereMonth('created_at', $month);

                // Filter tahun tertentu
                if ($year) $query->whereYear('created_at', $year);
            })
            ->with([
                'user:id,name',
                'pekerjaan:id,nasabah_id,perusahaan,golongan',
                'pengajuan:id,nasabah_id,nominal_pinjaman,tenor,status_pinjaman,pinjaman_ke,created_at',
                'pengajuan.approval:id,pengajuan_id,role,status,keterangan,user_id',
                'pengajuan.approval.user:id,name',
            ])
            ->get()
            ->sortBy('pengajuan.created_at'); // Urutkan data berdasarkan tanggal terbaru

        return view('admin.data-pengajuan', compact('riwayat'));
    }

    public function show($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('admin.detail-pengajuan', compact('nasabah'));
    }
}
