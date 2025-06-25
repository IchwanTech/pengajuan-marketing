<?php

namespace App\Http\Controllers\CreditAnalyst;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Nasabah;
use App\Models\NotifactionHeadMarketing;
use App\Models\NotifactionMarketing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalCAController extends Controller
{
    public function index()
    {
        $pengajuan = Nasabah::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuan', function ($query) {
                $query->whereIn('status', ['aproved spv', 'rejected spv', 'banding']);
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
            ->get()
            ->sortByDesc(function ($nasabah) {
                // Sorting by the latest 'created_at' in 'approval'
                return optional($nasabah->pengajuan('created_at'));
            });

        return view('creditAnalyst.approval-ca', compact('pengajuan'));
    }

    public function approval(Request $request, $id)
    {
        $userID = Auth::user()->id;

        $request->validate([
            'nominal_pinjaman' => 'required|numeric',
            'tenor' => 'required|numeric',
            'keterangan' => 'required',
            'kesimpulan' => 'nullable',
            'action' => 'required|in:approve,reject'
        ]);

        $pengajuan = Nasabah::findOrFail($id);

        if ($request->action == 'approve') {
            $pengajuan->pengajuan->status = 'aproved ca';
            $pengajuan->pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->pengajuan->tenor = $request->tenor;
            $pengajuan->pengajuan->save();

            Approval::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'user_id' => $userID,
                'role' => 'ca',
                'status' => 'approved',
                'keterangan' => $request->keterangan,
                'kesimpulan' => $request->kesimpulan
            ]);

            NotifactionHeadMarketing::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Pengajuan baru oleh Marketing ' . $pengajuan->user->name . ', telah di Approve ' . Auth::user()->name,
                'read' => false
            ]);

            return redirect()->route('creditAnalyst.approval')->with('success', 'Pengajuan berhasil disetujui');
        } elseif ($request->action == 'reject') {
            $pengajuan->pengajuan->status = 'rejected ca';
            $pengajuan->pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->pengajuan->tenor = $request->tenor;
            $pengajuan->pengajuan->save();

            Approval::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'user_id' => $userID,
                'role' => 'ca',
                'status' => 'rejected',
                'keterangan' => $request->keterangan,
                'kesimpulan' => $request->kesimpulan
            ]);

            NotifactionHeadMarketing::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Pengajuan baru oleh Marketing ' . $pengajuan->user->name . ', telah di Reject ' . Auth::user()->name,
                'read' => false
            ]);

            return redirect()->route('creditAnalyst.approval')->with('success', 'Pengajuan berhasil ditolak');
        }
    }

    public function approvalBanding(Request $request, $id)
    {
        $userID = Auth::user()->id;

        $request->validate([
            'nominal_pinjaman' => 'required|numeric',
            'tenor' => 'required|numeric',
            'keterangan' => 'required',
            'kesimpulan' => 'nullable',
            'action' => 'required|in:approve,reject'
        ]);

        $pengajuan = Nasabah::findOrFail($id);

        if ($request->action == 'approve') {
            $pengajuan->pengajuan->status = 'approved banding ca';
            $pengajuan->pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->pengajuan->tenor = $request->tenor;
            $pengajuan->pengajuan->save();

            Approval::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'user_id' => $userID,
                'role' => 'ca',
                'status' => 'approved',
                'is_banding' => 1,
                'keterangan' => $request->keterangan,
                'kesimpulan' => $request->kesimpulan
            ]);

            NotifactionHeadMarketing::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Pengajuan Banding oleh Marketing ' . $pengajuan->user->name . ', telah di Approve ' . Auth::user()->name . ' dengan pertimbangan ' . $request->keterangan,
                'read' => false
            ]);

            return redirect()->route('creditAnalyst.approval')->with('success', 'Pengajuan banding berhasil disetujui');
        } elseif ($request->action == 'reject') {
            $pengajuan->pengajuan->status = 'rejected banding ca';
            $pengajuan->pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->pengajuan->tenor = $request->tenor;
            $pengajuan->pengajuan->save();

            Approval::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'user_id' => $userID,
                'role' => 'ca',
                'status' => 'rejected',
                'is_banding' => 1,
                'keterangan' => $request->keterangan,
                'kesimpulan' => $request->kesimpulan
            ]);

            NotifactionHeadMarketing::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Pengajuan banding oleh Marketing ' . $pengajuan->user->name . ', telah di Reject ' . Auth::user()->name . ' dengan pertimbangan ' . $request->keterangan,
                'read' => false
            ]);

            return redirect()->route('creditAnalyst.approval')->with('success', 'Pengajuan banding berhasil ditolak');
        }
    }
    public function show($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::whereHas('pengajuan', function ($query) {
            $query->whereIn('status', ['aproved spv', 'rejected spv']);
        })
            ->with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval' => function ($query) {
                $query->where('role', 'spv'); // Hanya ambil approval dengan role spv
            }])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('creditAnalyst.detail-pengajuan', compact('nasabah'));
    }

    public function showBanding($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('creditAnalyst.detail-banding-pengajuan', compact('nasabah'));
    }
}
