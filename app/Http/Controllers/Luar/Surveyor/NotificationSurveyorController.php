<?php

namespace App\Http\Controllers\Luar\Surveyor;

use App\Http\Controllers\Controller;
use App\Models\Luar\NotificationSurveyor;
use Illuminate\Http\Request;

class NotificationSurveyorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notif = NotificationSurveyor::orderBy('created_at', 'desc')
            ->get();
        $unRead = $notif->where('read', false)->count();
        return view('pengajuan-luar.surveyor.notifikasi-surveyor', compact('notif', 'unRead'));
    }

    public function read($id)
    {
        $notification = NotificationSurveyor::findOrFail($id);

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
        NotificationSurveyor::where('read', false)->update(['read' => true]);

        return redirect()->back()
            ->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
