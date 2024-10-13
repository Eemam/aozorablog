<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderBy("id","desc")->with(['user', 'comments'])->get();

        return view('home', 
        [
            'posts'=> $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->user_id == null) {
            return back()->with('reject', 'Please login first!');
        }

        if($_FILES['image']['error'] == 0):

            $image = $_FILES['image'];

            $img_name = $image['name'];
            $img_ext = explode('.',$img_name);
            $img_ext = end($img_ext);
            $img_tmp_name = $image['tmp_name'];
            
            $new_name = date("dmy_his.");
            $new_name .= $img_ext;

            move_uploaded_file($img_tmp_name, "img/$new_name");

        endif;

        Post::create([
            'user_id' => $request->user_id,
            'body' => $request->body ?? null,
            'image' => $new_name ?? null,
        ]);

        return back()->with('success', 'Post updated!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if ($post) {
            $request->validate([
                'body' => 'required|string|max:1000',
            ]);

            // Update the post content
            $post->body = $request->body;
            $post->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the post by ID
        $post = Post::findOrFail($id);
        
        if($post->image):
            unlink("img/$post->image");
        endif;
    
        // Delete the post
        if ($post->delete()) {
            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully',
            ]);
        } else {
            // Return an error response if deletion fails
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post',
            ], 500);
        }
    }
}
