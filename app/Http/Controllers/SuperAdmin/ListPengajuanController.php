<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $filterTime = $request->input('filter_time');
        $month = $request->input('month');
        $year = $request->input('year');

        // Optimasi: Menggunakan join untuk performa yang lebih baik
        $availableYears = DB::table('nasabahs')
            ->join('pengajuans', 'nasabahs.id', '=', 'pengajuans.nasabah_id')
            ->selectRaw('YEAR(pengajuans.created_at) as year')
            ->whereNotNull('pengajuans.created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Query yang lebih efisien dengan eager loading
        $riwayat = Nasabah::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuan', function ($query) use ($filterTime, $month, $year) {
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
                'pengajuan' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->get();

        // Optimasi: Sorting sudah dilakukan di level database, tidak perlu sortBy lagi

        return view('superAdmin.list-pengajuan', compact('riwayat', 'availableYears'));
    }


    public function show($id)
    {
        // Optimasi: Eager loading dengan ordering untuk pengajuan
        $nasabah = Nasabah::with([
            'user',
            'alamat',
            'jaminan',
            'keluarga',
            'kerabat',
            'pekerjaan',
            'pengajuan' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'pengajuan.approval'
        ])
            ->findOrFail($id);

        return view('superAdmin.detail-pengajuan', compact('nasabah'));
    }
}
