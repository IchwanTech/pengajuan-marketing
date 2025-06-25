<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class ListPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $filterTime = $request->input('filter_time');
        $month = $request->input('month');
        $year = $request->input('year');

        // Ambil tahun yang tersedia di tabel pengajuans
        $availableYears = Nasabah::whereHas('pengajuan')
            ->selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $riwayat = Nasabah::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
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
                if ($month) {
                    $query->whereMonth('created_at', $month);
                }

                // Filter tahun tertentu
                if ($year) {
                    $query->whereYear('created_at', $year);
                }
            })
            ->with([
                'user',
                'alamat',
                'jaminan',
                'keluarga',
                'kerabat',
                'pekerjaan',
                'pengajuan'
            ])
            ->get()
            ->sortBy('pengajuan.created_at'); // Urutkan data berdasarkan tanggal terbaru

        return view('superAdmin.list-pengajuan', compact('riwayat', 'availableYears'));
    }

    public function show($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('superAdmin.detail-pengajuan', compact('nasabah'));
    }
}
