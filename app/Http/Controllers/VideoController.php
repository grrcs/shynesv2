<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index(): View
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $videos = Video::latest()->paginate(5);
        return view('videos.index', compact('videos'));
    }

    public function create(): View
    {
        return view('videos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'title'      => 'required|min:5',
            'video_file' => 'required|file|mimes:mp4,mov,ogg,qt|max:20000', // Max 20MB
            'caption'    => 'nullable'
        ]);

        // Upload Video
        $video = $request->file('video_file');
        $video->storeAs('videos', $video->hashName(), 'public');

        Video::create([
            'title'      => $request->title,
            'video_file' => $video->hashName(),
            'caption'    => $request->caption
        ]);

        return redirect()->route('videos.index')->with(['success' => 'Video Berhasil Diupload!']);
    }

    public function destroy($id): RedirectResponse
    {
        $video = Video::findOrFail($id);
        Storage::disk('public')->delete('videos/'.$video->video_file);
        $video->delete();

        return redirect()->route('videos.index')->with(['success' => 'Video Dihapus!']);
    }
}
