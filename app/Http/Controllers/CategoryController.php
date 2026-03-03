<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index(): View
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Ambil data kategori, urutkan terbaru, 5 per halaman
        $categories = Category::latest()->paginate(5);

        return view('categories.index', compact('categories'));
    }

    /**
     * create
     *
     * @return View
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // Validasi
        $this->validate($request, [
            'name'        => 'required|min:3',
            'description' => 'nullable|max:255'
        ]);

        // Simpan
        $category = Category::create([
            'name'        => $request->name,
            'description' => $request->description
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'category' => $category,
                'message' => 'Kategori Berhasil Dibuat!'
            ]);
        }

        return redirect()->route('categories.index')->with(['success' => 'Kategori Berhasil Dibuat!']);
    }

    /**
     * edit
     *
     * @param  mixed $id
     * @return View
     */
    public function edit(string $id): View
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|min:3'
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'name'        => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('categories.index')->with(['success' => 'Kategori Berhasil Diupdate!']);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with(['success' => 'Kategori Dihapus!']);
    }
}
