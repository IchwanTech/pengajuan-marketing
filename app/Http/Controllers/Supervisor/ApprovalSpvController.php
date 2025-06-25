<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Nasabah;
use App\Models\NotifactionCreditAnalyst;
use App\Models\NotifactionMarketing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalSpvController extends Controller
{
    public function index()
    {
        $pengajuan = Nasabah::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuan', function ($query) {
                $query->where('status', 'pending');
            })
            ->with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan'])
            ->get()
            ->sortByDesc(function ($nasabah) {
                // Sorting by the latest 'created_at' in 'approval'
                return optional($nasabah->pengajuan('created_at'));
            });

        return view('supervisor.approval', compact('pengajuan'));
    }

    public function approval(Request $request, $id)
    {
        $userID = Auth::user()->id;

        $request->validate([
            'keterangan' => 'required',
            'action' => 'required|in:approve,reject,approve fastrack'
        ]);

        $pengajuan = Nasabah::findOrFail($id);

        $nama = $pengajuan->user->name;

        if ($request->action == 'approve') {
            $pengajuan->pengajuan->status = 'aproved spv';
            $pengajuan->pengajuan->save();

            Approval::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'user_id' => $userID,
                'role' => 'spv',
                'status' => 'approved',
                'keterangan' => $request->keterangan
            ]);

            NotifactionCreditAnalyst::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Pengajuan baru oleh Marketing ' . $nama . ', telah di Approve Supervisor.',
                'read' => false
            ]);

            // NotifactionMarketing::create([
            //     'pengajuan_id' => $pengajuan->pengajuan->id,
            //     'pesan' => 'Pengajuan anda untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Approve Supervisor dan menunggu Approval Credit Analyst.',
            //     'read' => false
            // ]);

            return redirect()->route('supervisor.approval')->with('success', 'Pengajuan berhasil disetujui');
        } elseif ($request->action == 'reject') {
            $pengajuan->pengajuan->status = 'rejected spv';
            $pengajuan->pengajuan->save();

            Approval::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'user_id' => $userID,
                'role' => 'spv',
                'status' => 'rejected',
                'keterangan' => $request->keterangan
            ]);

            NotifactionCreditAnalyst::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'pesan' => 'Pengajuan baru oleh Marketing ' . $nama . ', telah di Reject Supervisor.',
                'read' => false
            ]);

            // NotifactionMarketing::create([
            //     'pengajuan_id' => $pengajuan->pengajuan->id,
            //     'pesan' => 'Pengajuan anda untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Reject Supervisor, alasan karena ' . $request->keterangan . '.',
            //     'read' => false
            // ]);

            return redirect()->route('supervisor.approval')->with('success', 'Pengajuan berhasil ditolak');
        } elseif ($request->action == 'approve fastrack') {
            $pengajuan->pengajuan->status = 'approved fastrack';
            $pengajuan->pengajuan->save();

            Approval::create([
                'pengajuan_id' => $pengajuan->pengajuan->id,
                'user_id' => $userID,
                'role' => 'spv',
                'status' => 'approved',
                'keterangan' => $request->keterangan
            ]);

            return redirect()->route('supervisor.approval')->with('success', 'Pengajuan berhasil disetujui dengan status Fastrack');
        }
    }

    public function approve(Request $request, $id)
    {
        $userID = Auth::user()->id;

        $request->validate([
            'keterangan' => 'required',
        ]);

        $pengajuan = Nasabah::findOrFail($id);
        $pengajuan->pengajuan->status = 'aproved spv';
        $pengajuan->pengajuan->save();

        Approval::create([
            'pengajuan_id' => $pengajuan->pengajuan->id,
            'user_id' => $userID,
            'role' => 'spv',
            'status' => 'approved',
            'keterangan' => $request->keterangan
        ]);

        NotifactionCreditAnalyst::create([
            'pengajuan_id' => $pengajuan->pengajuan->id,
            'pesan' => 'Pengajuan baru oleh Marketing ' . $pengajuan->user->where('usertype', 'marketing')->first()->name . ', telah di Approve Supervisor.',
            'read' => false
        ]);

        NotifactionMarketing::create([
            'pengajuan_id' => $pengajuan->pengajuan->id,
            'pesan' => 'Pengajuan anda untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Approve Supervisor dan menunggu Approval Credit Analyst.',
            'read' => false
        ]);

        return redirect()->route('supervisor.approval')->with('success', 'Pengajuan berhasil disetujui');
    }

    public function reject(Request $request, $id)
    {
        $userID = Auth::user()->id;

        $request->validate([
            'keterangan' => 'required',
        ]);

        $pengajuan = Nasabah::findOrFail($id);
        $pengajuan->pengajuan->status = 'rejected spv';
        $pengajuan->pengajuan->save();

        Approval::create([
            'pengajuan_id' => $pengajuan->pengajuan->id,
            'user_id' => $userID,
            'role' => 'spv',
            'status' => 'rejected',
            'keterangan' => $request->keterangan
        ]);

        NotifactionCreditAnalyst::create([
            'pengajuan_id' => $pengajuan->pengajuan->id,
            'pesan' => 'Pengajuan baru oleh Marketing ' . $pengajuan->user->where('usertype', 'marketing')->first()->name . ', telah di Reject Supervisor.',
            'read' => false
        ]);

        NotifactionMarketing::create([
            'pengajuan_id' => $pengajuan->pengajuan->id,
            'pesan' => 'Pengajuan anda untuk nasabah ' . $pengajuan->nama_lengkap . ' telah di Reject Supervisor, alasan karena ' . $request->keterangan . '.',
            'read' => false
        ]);

        return redirect()->route('supervisor.approval')->with('success', 'Pengajuan berhasil ditolak');
    }

    public function show($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('supervisor.detail-pengajuan', compact('nasabah'));
    }
}
