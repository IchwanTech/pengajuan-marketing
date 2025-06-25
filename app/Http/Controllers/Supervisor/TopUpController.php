<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\RepeatOrderManual;
use Illuminate\Http\Request;

class TopUpController extends Controller
{
    public function index()
    {
        $topUp = RepeatOrderManual::orderBy('created_at', 'desc')
            ->get();
        return view('supervisor.topup-pengajuan', compact('topUp'));
    }

    public function selesai($id)
    {
        $topUp = RepeatOrderManual::find($id);
        if (!$topUp->is_clear) {
            $topUp->is_clear = true;
            $topUp->save();
        }
        return redirect()->back()->with('success', 'Pengajuan top up telah selesai diinput.');
    }
}
