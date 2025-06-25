<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatApprovalHeadController extends Controller
{
    public function index(Request $request)
    {
        $filterTime = $request->input('filter_time');
        $month = $request->input('month');
        $year = $request->input('year');
        $currentYear = date('Y');

        // Ambil daftar tahun unik dari tabel nasabahs
        $availableYears = DB::table('nasabahs')
            ->select(DB::raw('YEAR(created_at) as year'))
            ->whereYear('created_at', '>=', $currentYear)
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year');

        $riwayat = Nasabah::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuan', function ($query) use ($filterTime, $month, $year) {
                $query->whereIn('status', ['aproved head', 'rejected head', 'approved banding head', 'rejected banding head']);

                // Filter waktu
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
                'pengajuan' => function ($query) {
                    $query->with(['approval' => function ($query) {
                        $query->where('role', 'hm'); // Load hanya approval dengan role 'hm'
                    }]);
                }
            ])
            ->get();

        $riwayat = $riwayat->sortBy(function ($nasabah) {
            return optional($nasabah->pengajuan->approval->first())->created_at; // Pastikan field sesuai
        });

        return view('headMarketing.riwayat-approval-hm', compact('riwayat', 'availableYears'));
    }

    public function show($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('headMarketing.detail-riwayat', compact('nasabah'));
    }
}
