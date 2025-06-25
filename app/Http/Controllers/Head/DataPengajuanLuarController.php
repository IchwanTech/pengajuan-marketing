<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Luar\ApprovalLuar;
use App\Models\Luar\NasabahLuar;
use App\Models\Luar\NotificationCA;
use App\Models\Luar\NotificationSPV;
use App\Models\Luar\PengajuanNasabahLuar;
use App\Models\NotifactionMarketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataPengajuanLuarController extends Controller
{
    public function index(Request $request)
    {
        $filterTime = $request->input('filter_time');
        $month = $request->input('month');
        $year = $request->input('year');

        $pengajuan = NasabahLuar::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuanLuar', function ($query) use ($filterTime, $month, $year) {
                $query->whereIn('status_pengajuan', ['aproved ca', 'rejected ca', 'approved banding ca', 'rejected banding ca']);
                // Filter berdasarkan waktu
                if ($filterTime == 'today') {
                    $query->whereDate('created_at', today());
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
                'pengajuanLuar' => function ($query) {
                    $query->whereIn('status_pengajuan', ['aproved ca', 'rejected ca', 'approved banding ca', 'rejected banding ca'])
                        ->orderBy('created_at', 'asc');
                }
            ])
            ->get();

        return view('headMarketing.data-pengajuan-luar', compact('pengajuan'));
    }

    public function show($id)
    {
        // $nasabah = NasabahLuar::with('alamatLuar', 'pekerjaanLuar', 'penjaminLuar', 'tanggunganLuar', 'pinjamanLainLuar', 'kontakDarurat', 'pengajuanLuar')->findOrFail($id);
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat')
            ->findOrFail($id);
        $isSurvey = $pengajuan->hasilSurvey()->exists();
        // dd($nasabah);
        return view('headMarketing.detail-pengajuan-luar', compact('pengajuan', 'isSurvey'));
    }

    public function approval(Request $request, $id)
    {
        $user = Auth::user()->id;

        $pengajuan = PengajuanNasabahLuar::findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'required'
        ]);

        $catatanCA = $pengajuan->approval->where('role', 'ca')->first()->catatan ?? 'Tidak ada catatan';

        if ($request->action == 'approve') {
            $pengajuan->status_pengajuan = 'aproved hm';
            $pengajuan->save();

            ApprovalLuar::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => $user,
                'role' => 'hm',
                'status' => 'approved',
                'catatan' => $request->catatan
            ]);

            NotifactionMarketing::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Selamat, Pengajuan anda untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Approve oleh Head Marketing, dengan nominal pinjaman Rp. ' . number_format($pengajuan->nominal_pinjaman, 0, ',', '.') . ' dan tenor: ' . $pengajuan->tenor . ' bulan, dengan Catatan Credit Analyst: ' . $catatanCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);

            NotificationCA::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Approve oleh Head Marketing, dengan nominal pinjaman Rp. ' . number_format($pengajuan->nominal_pinjaman, 0, ',', '.') . ' dan tenor: ' . $pengajuan->tenor . ' bulan, dengan Catatan Credit Analyst: ' . $catatanCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);

            NotificationSPV::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' oleh ' . $pengajuan->nasabahLuar->user->name . ' telah di Approve oleh Head Marketing, dengan nominal pinjaman Rp. ' . number_format($pengajuan->nominal_pinjaman, 0, ',', '.') . ' dan tenor: ' . $pengajuan->tenor . ' bulan, dengan Catatan Credit Analyst: ' . $catatanCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);
        } elseif ($request->action == 'reject') {
            $pengajuan->status_pengajuan = 'rejected hm';
            $pengajuan->save();

            ApprovalLuar::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => $user,
                'role' => 'hm',
                'status' => 'rejected',
                'catatan' => $request->catatan
            ]);

            NotifactionMarketing::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Maaf, Pengajuan anda untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Reject oleh Head Marketing dengan Catatan Credit Analyst: ' . $catatanCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);

            NotificationCA::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Reject oleh Head Marketing dengan Catatan Credit Analyst: ' . $catatanCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);

            NotificationSPV::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' oleh ' . $pengajuan->nasabahLuar->user->name . ' telah di Reject oleh Head Marketing dengan Catatan Credit Analyst: ' . $catatanCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);
        }

        $message = $request->action == 'approve' ? 'Pengajuan berhasil di approve' : 'Pengajuan berhasil di reject';

        return redirect()->route('headMarketing.data.pengajuan.luar')->with('success', $message);
    }

    public function showBanding($id)
    {
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat', 'hasilSurvey')->findOrFail($id);
        $isSurvey = $pengajuan->whereHas('hasilSurvey', function ($query) use ($pengajuan) {
            $query->where('pengajuan_id', $pengajuan->first()->id);
        })
            ->exists();

        return view('headMarketing.detail-banding-luar', compact('pengajuan', 'isSurvey'));
    }

    public function approvalBanding(Request $request, $id)
    {
        $pengajuan = PengajuanNasabahLuar::findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'required'
        ]);

        $catatanBandingCA = $pengajuan->approval->where('role', 'ca')->where('is_banding', '1')->first()->catatan ?? 'Tidak ada catatan';

        if ($request->action == 'approve') {
            $pengajuan->status_pengajuan = 'approved banding hm';
            $pengajuan->save();

            ApprovalLuar::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::user()->id,
                'role' => 'hm',
                'status' => 'approved',
                'catatan' => $request->catatan,
                'is_banding' => '1'
            ]);

            NotifactionMarketing::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Selamat, Pengajuan banding anda untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Approve oleh Head Marketing, dengan nominal pinjaman Rp. ' . number_format($pengajuan->nominal_pinjaman, 0, ',', '.') . ' dan tenor: ' . $pengajuan->tenor . ' bulan, dengan Catatan Credit Analyst: ' . $catatanBandingCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);

            NotificationCA::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan banding untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Approve oleh Head Marketing, dengan nominal pinjaman Rp. ' . number_format($pengajuan->nominal_pinjaman, 0, ',', '.') . ' dan tenor: ' . $pengajuan->tenor . ' bulan, dengan Catatan Credit Analyst: ' . $catatanBandingCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);

            NotificationSPV::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan banding untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' oleh ' . $pengajuan->nasabahLuar->user->name . ' telah di Approve oleh Head Marketing, dengan nominal pinjaman Rp. ' . number_format($pengajuan->nominal_pinjaman, 0, ',', '.') . ' dan tenor: ' . $pengajuan->tenor . ' bulan, dengan Catatan Credit Analyst: ' . $catatanBandingCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);
        } elseif ($request->action == 'reject') {
            $pengajuan->status_pengajuan = 'rejected banding hm';
            $pengajuan->save();

            ApprovalLuar::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::user()->id,
                'role' => 'hm',
                'status' => 'rejected',
                'catatan' => $request->catatan,
                'is_banding' => '1'
            ]);

            NotifactionMarketing::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Maaf, Pengajuan banding anda untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Reject oleh Head Marketing dengan Catatan Credit Analyst: ' . $catatanBandingCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);

            NotificationCA::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan banding untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Reject oleh Head Marketing dengan Catatan Credit Analyst: ' . $catatanBandingCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);

            NotificationSPV::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan banding untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' oleh ' . $pengajuan->nasabahLuar->user->name . ' telah di Reject oleh Head Marketing dengan Catatan Credit Analyst: ' . $catatanBandingCA . ', dan keterangan: ' . $request->catatan,
                'read' => false
            ]);
        }

        $message = $request->action == 'approve' ? 'Pengajuan banding berhasil di approve' : 'Pengajuan banding berhasil di reject';

        return redirect()->route('headMarketing.data.pengajuan.luar')->with('success', $message);
    }
}
