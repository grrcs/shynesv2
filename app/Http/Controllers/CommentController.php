<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'body'             => 'required|string|max:500',
            'commentable_id'   => 'required|integer',
            'commentable_type' => 'required|string',
        ]);

        Comment::create([
            'user_id'          => auth()->id(),
            'body'             => $request->body,
            'commentable_id'   => $request->commentable_id,
            'commentable_type' => $request->commentable_type,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim!');
    }
}
