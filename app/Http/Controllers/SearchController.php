<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Post;
use App\Models\Video;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->input('q');

        if (!$query) {
             return view('search.index', [
                 'products' => collect(), 
                 'posts' => collect(), 
                 'videos' => collect(), 
                 'query' => ''
             ]);
        }

        $products = Product::where('title', 'like', "%{$query}%")
                           ->orWhere('description', 'like', "%{$query}%")
                           ->get();
                           
        $posts = Post::where('title', 'like', "%{$query}%")
                     ->orWhere('content', 'like', "%{$query}%")
                     ->get();

        $videos = Video::where('title', 'like', "%{$query}%")
                       ->get();

        return view('search.index', compact('products', 'posts', 'videos', 'query'));
    }
}
