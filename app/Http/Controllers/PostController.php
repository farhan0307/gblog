<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

class PostController extends Controller
{
    //
    public  function WritePost(Request $request)
    {

        $rules = [
            'title' => 'required|string',
            'body' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $post = Post::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'uid' =>  $request->user()->id,
        ]);

        return response()->json(['post' => $post, 'message' => 'Post created'], 201);
    }
    public function Dell_Post(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'No Post'], 404);
        }
        $user = auth()->user();
        if ($user->role == "admin") {
            $post->delete();
            return response()->json(['message' => 'Post deleted']);
        }

        if ($post->uid !== $request->user()->id) {
            return response()->json(['error' => 'You have no Permission'], 403);
        }
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
    public function Edit_Update_Post($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'No Post'], 404);
        }
        $user = auth()->user();
        if ($user->id == $post->uid) {
            $post->body = request('body');
            $post->save();
            return response()->json(['message' => 'Post updated']);
        } else {
            return response()->json(['error' => 'You have no Permission'], 403);
        }
    }
    public function getPostById($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'No Post'], 404);
        }
        return response()->json(['post' => $post]);
    }
}
