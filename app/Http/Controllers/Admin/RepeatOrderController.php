<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotifactionSupervisor;
use App\Models\RepeatOrderManual;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RepeatOrderController extends Controller
{
    public function index()
    {
        $user = User::where('usertype', 'marketing')
            ->whereNotIn('id', [11, 12, 19, 24])
            ->get();

        return view('repeat-order', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'nominal_pinjaman' => 'required|numeric|min:0',
            'tenor' => 'required|numeric',
            'pinjaman_ke' => 'required|numeric',
            'marketing' => 'required|exists:users,id',
            'alasan_topup' => 'nullable|string'
        ]);

        $marketing = User::findOrFail($request->marketing);

        DB::beginTransaction();

        try {
            RepeatOrderManual::create([
                'marketing_id' => $marketing->id,
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'no_hp' => $request->no_hp,
                'nominal_pinjaman' => $request->nominal_pinjaman,
                'tenor' => $request->tenor,
                'pinjaman_ke' => $request->pinjaman_ke,
                'nama_marketing' => $marketing->name,
                'status_konsumen' => 'lama',
                'alasan_topup' => $request->alasan_topup,
            ]);

            NotifactionSupervisor::create([
                'pengajuan_id' => null,
                'pesan' => 'Pengajuan Top Up Ulang Nasabah dengan nama ' . $request->nama_lengkap . ' untuk Marketing ' . $marketing->name . ' dengan nominal pengajuan Rp. ' . number_format($request->nominal_pinjaman, 0, ',', '.') . ' dan tenor ' . $request->tenor . ' bulan, dengan alasan topup: ' . $request->alasan_topup . '. Silahkan beritahu marketing terkait untuk dapat menginputan data nasabah.',
                'read' => false
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pengajuan Berhasil');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            Log::error('Error saat menyimpan data:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
