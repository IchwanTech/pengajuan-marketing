<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class ListApprovalController extends Controller
{
    public function show($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        return view('superAdmin.list-approval', compact('nasabah'));
    }

    public function update(Request $request, $id)
    {
        $approval = Approval::findOrFail($id);

        if ($request->has('status')) {
            $request->validate([
                'status' => 'required|in:approved,rejected',
            ]);
            $approval->status = $request->status;
        }

        if ($request->has('keterangan')) {
            $request->validate([
                'keterangan' => 'nullable|string',
            ]);
            $approval->keterangan = $request->keterangan;
        }

        $approval->save();

        return redirect()->back()->with('success', 'Approval berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $approval = Approval::findOrFail($id);
        $approval->delete();

        return redirect()->back()->with('success', 'Approval berhasil dihapus.');
    }
}
