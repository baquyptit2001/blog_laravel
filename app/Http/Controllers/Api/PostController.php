<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $category
     * @param $sort
     * @param $page
     * @param $size
     * @return JsonResponse
     */
    public function index($category, $sort, $page, $size): JsonResponse
    {
        $sortings = array(
          1 => 'asc',
          2 => 'desc',
        );
        if ($category) {
            $posts = Post::whereIn('category_id', explode(',', $category ))->orderBy('id', $sortings[$sort])->offset(($page - 1) * $size)->limit($size)->get();
        } else {
            $posts = Post::orderBy('id', $sortings[$sort])->offset(($page - 1) * $size)->limit($size)->get();
        }
        return response()->json(array(
            'posts' => PostResource::collection($posts),
            'total' => $category == 0 ? Post::count() : Post::whereIn('category_id', explode(',', $category ))->count(),
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json([
            'data' => new PostResource($post),
            'status' => 'success',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
