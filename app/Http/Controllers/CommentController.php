<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    public function WriteComment(Request $request)
    {
         // Validate the Request
        $post = Post::find($request->input('postid'));
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        $request->validate([
            'name' => 'string',
            'body' => 'required|string',
            'postid' => 'required|exists:posts,id',
        ]);
        Comment::create([
            'name' => $request->input('name'),
            'body' => $request->input('body'),
            'postid' => $request->input('postid'),
        ]);
        return response()->json(['message'=>'Comment Add Successfully']);
    }
    public function getCommentsByPostId($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        $comments = Comment::where('postid', $id)->get();
        return response()->json(['comments' => $comments]);
    }

    public function Dell_Comment($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }


        $user = auth()->user();
        if ($user->role == "admin") {
            $comment->delete();
            return response()->json(['message' => 'Comment Deleted']);
        }
        if ($comment->post->uid == auth()->user()->id) {

            $comment->delete();
            return response()->json(['message' => 'Comment Deleted']);
        }
        return response()->json(['message' => 'Not allowed to delete comment']);
    }
}

