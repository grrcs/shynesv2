<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category; // <--- Import Model Category
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $search = $request->input('search');

        // Tambahkan with('category') agar query lebih efisien (Eager Loading)
        $query = Post::with('category')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(5);
        $posts->appends(['search' => $search]);

        return view('posts.index', compact('posts'));
    }

    public function create(): View
    {
        // Ambil semua kategori untuk dropdown
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'       => 'required|min:5',
            'content'     => 'required|min:10',
            'status'      => 'required|in:publish,draft',
            'category_id' => 'required|exists:categories,id', // Validasi Kategori
        ]);

        $image = $request->file('image');
        $image->storeAs('posts', $image->hashName(), 'public');

        Post::create([
            'image'       => $image->hashName(),
            'title'       => $request->title,
            'content'     => $request->content,
            'status'      => $request->status,
            'category_id' => $request->category_id, // Simpan ID Kategori
        ]);

        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id): View
    {
        $post = Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }

    public function edit(string $id): View
    {
        $post = Post::findOrFail($id);
        // Ambil kategori juga untuk halaman edit
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'image'       => 'image|mimes:jpeg,jpg,png|max:2048',
            'title'       => 'required|min:5',
            'content'     => 'required|min:10',
            'status'      => 'required|in:publish,draft',
            'category_id' => 'required|exists:categories,id', // Validasi
        ]);

        $post = Post::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('posts', $image->hashName(), 'public');
            Storage::disk('public')->delete('posts/'.$post->image);

            $post->update([
                'image'       => $image->hashName(),
                'title'       => $request->title,
                'content'     => $request->content,
                'status'      => $request->status,
                'category_id' => $request->category_id
            ]);
        } else {
            $post->update([
                'title'       => $request->title,
                'content'     => $request->content,
                'status'      => $request->status,
                'category_id' => $request->category_id
            ]);
        }

        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy($id): RedirectResponse
    {
        $post = Post::findOrFail($id);
        Storage::disk('public')->delete('posts/'. $post->image);
        $post->delete();
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
