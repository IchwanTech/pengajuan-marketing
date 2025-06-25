<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Nasabah;
use App\Models\NotifactionMarketing;
use App\Models\NotifactionSupervisor;
use App\Models\NotifactionCreditAnalyst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalHeadController extends Controller
{
    public function index()
    {
        $pengajuan = Nasabah::whereHas('user', function ($query) {
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
                'pengajuan.approval'
            ])
            ->get()
            ->sortByDesc(function ($nasabah) {
                // Sorting by the latest 'created_at' in 'approval'
                return optional($nasabah->pengajuan('created_at'));
            });

        return view('headMarketing.approval-hm', compact('pengajuan'));
    }

    public function approval(Request $request, $id)
    {
        $userID = Auth::user()->id;

        $request->validate([
            'nominal_pinjaman' => 'required|numeric',
            'tenor' => 'required|numeric',
            'keterangan' => 'required',
            'action' => 'required|in:approve,reject',
            'enable_edit' => 'required|boolean'
        ]);

        $pengajuan = Nasabah::findOrFail($id);

        $pengajuan->enable_edit = $request->enable_edit;
        $pengajuan->save();

        $kesimpulanCA = $pengajuan->pengajuan->approval->where('role', 'ca')->first()->kesimpulan ?? 'Tidak ada kesimpulan';

        if ($pengajuan->pekerjaan->golongan == 'Borongan') {
            $waktu = 'Periode';
        } else {
            $waktu = 'Bulan';
        }

        if ($request->action == 'approve') {
            $pengajuan->pengajuan->status = 'aproved head';
            $pengajuan->pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->pengajuan->tenor = $request->tenor;
            $pengajuan->pengajuan->save();

            Approval::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'user_id' => $userID,
                'role' => 'hm',
                'status' => 'approved',
                'keterangan' => $request->keterangan
            ]);

            NotifactionMarketing::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Selamat, Pengajuan anda untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Approve oleh ' . Auth::user()->name . ' dengan nominal pinjaman Rp. ' . number_format($request->nominal_pinjaman, 0, ',', '.') . ' dan tenor ' . $request->tenor . ' ' . $waktu . ' , dengan kesimpulan Credit Analyst: ' . $kesimpulanCA . ' dan keterangan: ' . $request->keterangan,
                'read' => false
            ]);

            NotifactionCreditAnalyst::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Selamat, Pengajuan untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Approve oleh ' . Auth::user()->name . ' dengan nominal pinjaman Rp. ' . number_format($request->nominal_pinjaman, 0, ',', '.') . ' dan tenor ' . $request->tenor . ' ' . $waktu . ' , dengan kesimpulan Credit Analyst: ' . $kesimpulanCA . ' dan keterangan: ' . $request->keterangan,
                'read' => false
            ]);

            NotifactionSupervisor::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Selamat, Pengajuan untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Approve oleh ' . Auth::user()->name . ' dengan nominal pinjaman Rp. ' . number_format($request->nominal_pinjaman, 0, ',', '.') . ' dan tenor ' . $request->tenor . ' ' . $waktu . ' , dengan kesimpulan Credit Analyst: ' . $kesimpulanCA . ' dan keterangan: ' . $request->keterangan,
                'read' => false
            ]);

            return redirect()->route('headMarketing.approval')->with('success', 'Pengajuan berhasil disetujui');
        } elseif ($request->action == 'reject') {
            $pengajuan->pengajuan->status = 'rejected head';
            $pengajuan->pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->pengajuan->tenor = $request->tenor;
            $pengajuan->pengajuan->save();

            Approval::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'user_id' => $userID,
                'role' => 'hm',
                'status' => 'rejected',
                'keterangan' => $request->keterangan
            ]);

            NotifactionMarketing::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Mohon maaf, Pengajuan anda untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Reject ' . Auth::user()->name . ' dengan kesimpulan Credit Analyst: ' . $kesimpulanCA . ' dan keterangan: ' . $request->keterangan,
                'read' => false
            ]);

            NotifactionCreditAnalyst::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Mohon maaf, Pengajuan untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Reject ' . Auth::user()->name . ' dengan kesimpulan Credit Analyst: ' . $kesimpulanCA . ' dan keterangan: ' . $request->keterangan,
                'read' => false
            ]);

            NotifactionSupervisor::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Mohon maaf, Pengajuan untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Reject ' . Auth::user()->name . ' dengan kesimpulan Credit Analyst: ' . $kesimpulanCA . ' dan keterangan: ' . $request->keterangan,
                'read' => false
            ]);

            return redirect()->route('headMarketing.approval')->with('success', 'Pengajuan berhasil ditolak');
        }
    }

    public function approvalBanding(Request $request, $id)
    {
        $userID = Auth::user()->id;

        $request->validate([
            'nominal_pinjaman' => 'required|numeric',
            'tenor' => 'required|numeric',
            'keterangan' => 'required',
            'action' => 'required|in:approve,reject'
        ]);

        $pengajuan = Nasabah::findOrFail($id);

        if ($pengajuan->pekerjaan->golongan == 'Borongan') {
            $waktu = 'Periode';
        } else {
            $waktu = 'Bulan';
        }

        if ($request->action == 'approve') {
            $pengajuan->pengajuan->status = 'approved banding head';
            $pengajuan->pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->pengajuan->tenor = $request->tenor;
            $pengajuan->pengajuan->is_banding = 2;
            $pengajuan->pengajuan->save();

            Approval::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'user_id' => $userID,
                'role' => 'hm',
                'status' => 'approved',
                'is_banding' => 1,
                'keterangan' => $request->keterangan
            ]);

            NotifactionMarketing::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Selamat, Pengajuan banding anda untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Approve oleh ' . Auth::user()->name . ' dengan nominal pinjaman Rp. ' . number_format($request->nominal_pinjaman, 0, ',', '.') . ' dan tenor ' . $request->tenor . ' ' . $waktu . ' , dengan keterangan: ' . $request->keterangan,
                'read' => false
            ]);

            NotifactionCreditAnalyst::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Selamat, Pengajuan banding untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Approve oleh ' . Auth::user()->name . ' dengan nominal pinjaman Rp. ' . number_format($request->nominal_pinjaman, 0, ',', '.') . ' dan tenor ' . $request->tenor . ' ' . $waktu . ' , dengan keterangan: ' . $request->keterangan,
                'read' => false
            ]);

            NotifactionSupervisor::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Selamat, Pengajuan banding untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Approve oleh ' . Auth::user()->name . ' dengan nominal pinjaman Rp. ' . number_format($request->nominal_pinjaman, 0, ',', '.') . ' dan tenor ' . $request->tenor . ' ' . $waktu . ' , dengan keterangan: ' . $request->keterangan,
                'read' => false
            ]);

            return redirect()->route('headMarketing.approval')->with('success', 'Pengajuan banding berhasil disetujui');
        } elseif ($request->action == 'reject') {
            $pengajuan->pengajuan->status = 'rejected banding head';
            $pengajuan->pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->pengajuan->tenor = $request->tenor;
            $pengajuan->pengajuan->is_banding = 2;
            $pengajuan->pengajuan->save();

            Approval::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'user_id' => $userID,
                'role' => 'hm',
                'status' => 'rejected',
                'is_banding' => 1,
                'keterangan' => $request->keterangan
            ]);

            NotifactionMarketing::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Mohon maaf, Pengajuan banding anda untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Reject ' . Auth::user()->name . ' dengan keterangan ' . $request->keterangan,
                'read' => false
            ]);

            NotifactionCreditAnalyst::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Mohon maaf, Pengajuan banding untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Reject ' . Auth::user()->name . ' dengan keterangan ' . $request->keterangan,
                'read' => false
            ]);

            NotifactionSupervisor::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Mohon maaf, Pengajuan banding untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Reject ' . Auth::user()->name . ' dengan keterangan ' . $request->keterangan,
                'read' => false
            ]);

            return redirect()->route('headMarketing.approval')->with('success', 'Pengajuan banding berhasil ditolak');
        }
    }

    public function show($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('headMarketing.detail-pengajuan', compact('nasabah'));
    }

    public function showBanding($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('headMarketing.detail-banding-pengajuan', compact('nasabah'));
    }
}
