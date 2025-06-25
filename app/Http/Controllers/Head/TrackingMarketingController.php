<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingMarketingController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $currentYear = date('Y');

        // Ambil daftar tahun unik dari tabel nasabahs, mulai dari tahun ini
        $availableYears = DB::table('nasabahs')
            ->select(DB::raw('YEAR(created_at) as year'))
            ->whereYear('created_at', '>=', $currentYear)
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year');

        $marketing = User::where('usertype', 'marketing')
            ->leftJoin('nasabahs', 'users.id', '=', 'nasabahs.marketing_id')
            ->leftJoin('pengajuans', 'nasabahs.id', '=', 'pengajuans.nasabah_id')
            ->select(
                'users.name as marketing_name',
                DB::raw('COUNT(nasabahs.id) as total_nasabah'),
                DB::raw('SUM(CASE WHEN pengajuans.status IN ("aproved head", "approved banding head") THEN 1 ELSE 0 END) as total_approved_hm'),
                DB::raw('SUM(CASE WHEN pengajuans.status IN ("rejected head", "rejected banding head") THEN 1 ELSE 0 END) as total_rejected_hm')
            )
            ->when($month, function ($query, $month) {
                return $query->whereMonth('pengajuans.created_at', $month);
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('pengajuans.created_at', $year);
            })
            ->groupBy('users.id', 'users.name')
            ->get();

        return view('headMarketing.tracking-marketing', compact('marketing', 'availableYears'));
    }
}
