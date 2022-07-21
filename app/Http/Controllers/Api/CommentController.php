<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentEvent;
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
        event(new CommentEvent(new CommentResource($comment), $comment->post_id));
        return response()->json([
            'comments' => new CommentResource($comment),
        ]);
    }

    public function post_comment(Post $post, $page, $size = 5)
    {
        $comments = Comment::where('post_id', $post->id)->orderBy('created_at', 'desc')->offset(($page - 1) * $size)->limit($size)->get();
        $total = Comment::where('post_id', $post->id)->count();
        return response()->json([
            'comments' => CommentResource::collection($comments),
            'total' => $total,
        ]);
    }

    public function post_comment_offset(Post $post, $offset, $size = 5)
    {
        $comments = Comment::where('post_id', $post->id)->orderBy('id', 'desc')->offset($offset)->limit($size)->get();
        $total = Comment::where('post_id', $post->id)->count();
        return response()->json([
            'comments' => CommentResource::collection($comments),
            'is_end' => $total <= $offset + $size,
        ]);
    }
}
