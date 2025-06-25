<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class CheckVoucherController extends Controller
{
    public function index()
    {
        // $voucher = Voucher::all();
        return view('voucher-check');
    }

    public function checkVoucher(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'nik' => 'required|string',
        ]);

        // Query untuk menemukan semua voucher berdasarkan nama dan NIK
        $vouchers = Voucher::where('nama', $request->nama)
            ->where('nik', $request->nik)
            ->get();

        // Jika tidak ditemukan, kembali dengan pesan error
        if ($vouchers->isEmpty()) {
            return redirect()->back()->withErrors(['message' => 'Voucher tidak ditemukan!']);
        }

        // Validasi kadaluarsa untuk setiap voucher
        $today = now();
        $vouchers->each(function ($voucher) use ($today) {
            $voucher->isExpired = $voucher->kadaluarsa < $today; // Tambahkan status kadaluarsa
        });

        // Jika ditemukan, tampilkan semua voucher
        return view('voucher-check', compact('vouchers'));
    }
}
