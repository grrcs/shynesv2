<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Confession;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ConfessionController extends Controller
{
    public function index(): View
    {
        $confessions = Confession::latest()->paginate(10);
        return view('confessions.index', compact('confessions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'sender_name' => 'nullable|string|max:50',
        ]);

        Confession::create([
            'content' => $request->content,
            'sender_name' => $request->sender_name ?: 'Anonymous',
        ]);

        return back()->with('success', 'Pesan menfess terkirim!');
    }
}
