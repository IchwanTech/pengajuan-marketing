<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Luar\ApprovalLuar;
use App\Models\Luar\PengajuanNasabahLuar;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Http\Request;

class HDashboardController extends Controller
{

    public function index()
    {
        $dashboard = Nasabah::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuan', function ($query) {
                $query->whereIn('status', ['aproved ca', 'rejected ca', 'approved banding ca', 'rejected banding ca']);
            })
            ->with([
                'user',
                'alamat',
                'jaminan',
                'keluarga',
                'kerabat',
                'pekerjaan',
                'pengajuan.approval' => function ($query) {
                    $query->where('role', 'hm'); // Hanya ambil approval dengan role spv
                }
            ])
            ->get();

        $dashboardLuar = PengajuanNasabahLuar::with('nasabahLuar')
            ->whereHas('nasabahLuar', function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('usertype', 'marketing');
                });
            })
            ->whereIn('status_pengajuan', ['aproved ca', 'rejected ca', 'approved banding ca', 'rejected banding ca'])
            ->get();

        $approvedCount = \App\Models\Approval::where('role', 'hm')
            ->where('status', 'approved')
            ->count();

        $rejectedCount = \App\Models\Approval::where('role', 'hm')
            ->where('status', 'rejected')
            ->count();

        $approvedCountLuar = ApprovalLuar::where('role', 'hm')
            ->where('status', 'approved')
            ->count();

        $rejectedCountLuar = ApprovalLuar::where('role', 'hm')
            ->where('status', 'rejected')
            ->count();

        return view('headMarketing.dashboard-head', compact('dashboard', 'approvedCount', 'rejectedCount', 'approvedCountLuar', 'rejectedCountLuar', 'dashboardLuar'));
    }
}
