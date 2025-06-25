<?php

namespace App\Http\Controllers\CreditAnalyst;

use App\Http\Controllers\Controller;
use App\Models\Luar\NotificationCA;
use App\Models\NotifactionCreditAnalyst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationCAController extends Controller
{
    public function index()
    {
        $caType = Auth::user()->type;

        $notif = [];

        if ($caType === 'dalam') {
            $notif =
                NotifactionCreditAnalyst::orderBy('created_at', 'desc')
                ->get();
        } elseif ($caType === 'luar') {
            $notif = NotificationCA::orderBy('created_at', 'desc')
                ->get();
        }

        return view('creditAnalyst.notification-ca', compact('notif'));
    }

    public function read($id)
    {
        $caType = Auth::user()->type;

        $notification = [];

        if ($caType === 'dalam') {
            $notification = NotifactionCreditAnalyst::findOrFail($id);
        } elseif ($caType === 'luar') {
            $notification = NotificationCA::findOrFail($id);
        }

        // Update kolom read jika belum dibaca
        if (!$notification->read) {
            $notification->read = true;
            $notification->save();
        }

        return redirect()->back()
            ->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
    }

    public function readAll()
    {
        $caType = Auth::user()->type;

        if ($caType === 'dalam') {
            NotifactionCreditAnalyst::where('read', false)->update(['read' => true]);
        } elseif ($caType === 'luar') {
            NotificationCA::where('read', false)->update(['read' => true]);
        }

        return redirect()->back()
            ->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
