<?php

namespace App\Http\Controllers\Luar\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Luar\ApprovalLuar;
use App\Models\Luar\NasabahLuar;
use App\Models\Luar\NotificationCA;
use App\Models\Luar\PengajuanNasabahLuar;
use App\Models\NotifactionMarketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPengajuanLuarController extends Controller
{
    public function index()
    {
        $pengajuan = NasabahLuar::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuanLuar', function ($query) {
                $query->whereIn('status_pengajuan', ['pending', 'revisi spv']);
            })
            ->with([
                'user',
                'alamatLuar',
                'pekerjaanLuar',
                'penjaminLuar',
                'tanggunganLuar',
                'pinjamanLainLuar',
                'kontakDarurat',
                'pengajuanLuar' => function ($query) {
                    $query->whereIn('status_pengajuan', ['pending', 'revisi spv']);
                }
            ])
            ->get();

        return view('pengajuan-luar.supervisor.pengajuan-luar', compact('pengajuan'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat')
            ->findOrFail($id);

        return view('pengajuan-luar.supervisor.detail-pengajuan-luar', compact('pengajuan'));
    }

    public function check(Request $request, $id)
    {
        $pengajuan = PengajuanNasabahLuar::findOrFail($id);

        $request->validate([
            'hasil_checking' => 'required|string',
            'catatan_spv' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        // dd($request->all());

        // if ($pengajuan) {

        if ($request->hasil_checking == 'revisi') {
            $pengajuan->status_pengajuan = 'revisi spv';
            $pengajuan->catatan_spv = $request->catatan_spv;
            $pengajuan->save();

            NotifactionMarketing::create([
                'pengajuan_id' => null,
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan anda untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' sudah dicheck oleh Supervisor, dan perlu untuk diperbaiki oleh Marketing. Dengan catatan ' . $request->catatan_spv . '.',
                'read' => false
            ]);
        } elseif ($request->hasil_checking == 'clear') {
            $pengajuan->status_pengajuan = 'checked by spv';
            $pengajuan->save();

            ApprovalLuar::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::user()->id,
                'role' => 'spv',
                'catatan' => $request->catatan,
                'status' => 'checked',
            ]);

            NotifactionMarketing::create([
                'pengajuan_id' => null,
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan anda untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' sudah dicheck oleh Supervisor, dan akan di proses oleh Credit Analyst.',
                'read' => false
            ]);

            NotificationCA::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan baru oleh Marketing ' . $pengajuan->nasabahLuar->user->name . ' untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' sudah dicheck oleh Supervisor, dan menunggu approval Credit Analyst.',
                'read' => false
            ]);
        }

        return redirect()->route('supervisor.luar')->with('success', 'Pengajuan berhasil di check');
    }
}
