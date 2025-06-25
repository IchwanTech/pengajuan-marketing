<?php

namespace App\Http\Controllers\CreditAnalyst;

use App\Http\Controllers\Controller;
use App\Models\Luar\ApprovalLuar;
use App\Models\Luar\PengajuanNasabahLuar;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CADashboardController extends Controller
{
    public function index()
    {
        $caType = Auth::user()->type;

        if ($caType === 'dalam') {
            $dashboard = Nasabah::whereHas('user', function ($query) {
                $query->where('usertype', 'marketing');
            })
                ->whereHas('pengajuan', function ($query) {
                    $query->whereIn('status', ['aproved spv', 'rejected spv']);
                })
                ->with([
                    'user',
                    'alamat',
                    'jaminan',
                    'keluarga',
                    'kerabat',
                    'pekerjaan',
                    'pengajuan.approval' => function ($query) {
                        $query->where('role', 'ca'); // Hanya ambil approval dengan role spv
                    }
                ])
                ->get();

            $approvedCount = \App\Models\Approval::where('role', 'ca')
                ->where('status', 'approved')
                ->count();

            $rejectedCount = \App\Models\Approval::where('role', 'ca')
                ->where('status', 'rejected')
                ->count();

            return view('creditAnalyst.dashboard-ca', compact('dashboard', 'approvedCount', 'rejectedCount'));
        } elseif ($caType === 'luar') {
            $dashboardLuar = PengajuanNasabahLuar::with('nasabahLuar')
                ->whereHas('nasabahLuar', function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('usertype', 'marketing');
                    });
                })
                ->whereIn('status_pengajuan', ['checked by spv', 'banding', 'revisi', 'perlu survey', 'tidak perlu survey', 'verifikasi', 'survey selesai'])
                ->get();

            $approvedCountLuar = ApprovalLuar::where('role', 'ca')
                ->where('status', 'approved')
                ->count();

            $rejectedCountLuar = ApprovalLuar::where('role', 'ca')
                ->where('status', 'rejected')
                ->count();

            return view('pengajuan-luar.ca-luar.dashboard-ca-luar', compact('dashboardLuar', 'approvedCountLuar', 'rejectedCountLuar'));
        }
    }
}
