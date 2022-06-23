<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Post $post, User $user)
    {
        return response()->json([
            'comments' => CommentResource::collection(Comment::where([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ])->get()),
        ]);
    }

    public function store(Post $post, User $user, Request $request)
    {
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->post_id = $post->id;
        $comment->content = $request->content;
        $comment->save();
        return response()->json([
            'comments' => new CommentResource($comment),
        ]);
    }
}
