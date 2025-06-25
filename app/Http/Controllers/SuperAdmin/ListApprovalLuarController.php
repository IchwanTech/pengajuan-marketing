<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Luar\ApprovalLuar;
use App\Models\Luar\PengajuanNasabahLuar;
use Illuminate\Http\Request;

class ListApprovalLuarController extends Controller
{
    public function show($id)
    {
        $pengajuan = PengajuanNasabahLuar::with('approval', 'nasabahLuar', 'approval.user')
            ->findOrFail($id);
        return view('superAdmin.list-approval-pengajuan-luar', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $approval = ApprovalLuar::findOrFail($id);

        if ($request->has('status')) {
            $request->validate([
                'status' => 'required|in:approved,rejected',
            ]);

            $approval->status = $request->status;
        }

        if ($request->has('keterangan')) {
            $request->validate([
                'keterangan' => 'required|string|max:255',
            ]);

            $approval->keterangan = $request->keterangan;
        }

        $approval->save();

        return redirect()->back()->with('success', 'Status pengajuan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $approval = ApprovalLuar::findOrFail($id);
        $approval->delete();

        return redirect()->back()->with('success', 'Data pengajuan berhasil dihapus!');
    }
}
