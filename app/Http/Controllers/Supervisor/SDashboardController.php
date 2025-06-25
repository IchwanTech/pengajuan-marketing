<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Luar\ApprovalLuar;
use App\Models\Luar\PengajuanNasabahLuar;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SDashboardController extends Controller
{
    public function index()
    {
        $spvType = Auth::user()->type;

        if ($spvType === 'dalam') {

            $dashboard = Nasabah::whereHas('user', function ($query) {
                $query->where('usertype', 'marketing');
            })
                ->whereHas('pengajuan', function ($query) {
                    $query->where('status', 'pending');
                })
                ->with([
                    'user',
                    'alamat',
                    'jaminan',
                    'keluarga',
                    'kerabat',
                    'pekerjaan',
                    'pengajuan.approval' => function ($query) {
                        $query->where('role', 'spv'); // Hanya ambil approval dengan role spv
                    }
                ])
                ->get();

            $approvedCount = \App\Models\Approval::where('role', 'spv')
                ->where('status', 'approved')
                ->count();

            $rejectedCount = \App\Models\Approval::where('role', 'spv')
                ->where('status', 'rejected')
                ->count();

            return view('supervisor.dashboard-spv', compact('dashboard', 'approvedCount', 'rejectedCount'));
        } elseif ($spvType === 'luar') {
            $dashboardLuar = PengajuanNasabahLuar::with('nasabahLuar')
                ->whereHas('nasabahLuar', function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('usertype', 'marketing');
                    });
                })
                ->whereIn('status_pengajuan', ['pending', 'revisi spv'])
                ->get();

            $approvedCountLuar = ApprovalLuar::where('role', 'spv')
                ->where('status', 'checked')
                ->count();

            $rejectedCountLuar = ApprovalLuar::where('role', 'spv')
                ->where('status', 'rejected')
                ->count();

            return view('pengajuan-luar.supervisor.dashboard-spv-luar', compact('dashboardLuar', 'approvedCountLuar', 'rejectedCountLuar'));
        }
    }
}
