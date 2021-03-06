<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view('posts.index', [
            'posts' => Post::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $categories = Category::orderBy('sort_order', 'asc')->get();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'post' => 'required|string',
        ]);
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ]);
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('thumbnail'), $imageName);
        } else {
            return redirect()->back()->with('error', 'Please select an image');
        }
        $post = new Post();
        $post->title = $request->title;
        $post->category_id = $request->category_id;
        $post->content = $request->post;
        $post->user_id = auth()->id();
        $post->image = asset('thumbnail/' . $imageName);
        $post->save();
        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $slug
     * @return Application|Factory|View
     */
    public function edit($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $categories = Category::orderBy('sort_order', 'asc')->get();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $slug
     * @return RedirectResponse
     */
    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'post' => 'required|string',
        ]);
        $post = Post::where('slug', $slug)->first();
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ]);
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('thumbnail'), $imageName);
            $post->image = 'thumbnail/' . $imageName;
        }
        $post->title = $request->title;
        $post->category_id = $request->category_id;
        $post->content = $request->post;
        $post->save();
        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
