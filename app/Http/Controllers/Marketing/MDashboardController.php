<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Luar\NasabahLuar;
use App\Models\Luar\PengajuanNasabahLuar;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $dashboardDalam = Nasabah::where('marketing_id', $user->id)
            ->with('pengajuan')
            ->get();

        $dashboardLuar = PengajuanNasabahLuar::with('nasabahLuar')
            ->whereHas('nasabahLuar', function ($query) use ($user) {
                $query->where('marketing_id', $user->id);
            })
            ->get();
        // NasabahLuar::where('marketing_id', $user->id)
        //     ->with('pengajuanLuar')
        //     ->get();

        $totalPendingHm = Nasabah::where('marketing_id', $user->id)
            ->whereHas('pengajuan', function ($query) {
                $query->whereNotIn('status', [
                    'aproved head',
                    'approved banding head',
                    'rejected head',
                    'rejected banding head'
                ]);
            })
            ->count();

        $totalApprovedHm = Nasabah::where('marketing_id', $user->id)
            ->whereHas('pengajuan', function ($query) {
                $query->whereIn('status', ['aproved head', 'approved banding head']);
            })
            ->count();

        $totalApprovedLuar = NasabahLuar::where('marketing_id', $user->id)
            ->whereHas('pengajuanLuar', function ($query) {
                $query->whereIn('status_pengajuan', ['aproved ca', 'approved banding']);
            })
            ->count();

        $totalRejectedHm = Nasabah::where('marketing_id', $user->id)
            ->whereHas('pengajuan', function ($query) {
                $query->whereIn('status', ['rejected head', 'rejected banding head']);
            })
            ->count();

        $totalRejectedLuar = NasabahLuar::where('marketing_id', $user->id)
            ->whereHas('pengajuanLuar', function ($query) {
                $query->whereIn('status_pengajuan', ['rejected ca', 'rejected banding']);
            })
            ->count();

        return view('marketing.dashboard-marketing', compact('dashboardDalam', 'dashboardLuar', 'totalPendingHm', 'totalApprovedHm', 'totalRejectedHm', 'totalApprovedLuar', 'totalRejectedLuar'));
    }
}
