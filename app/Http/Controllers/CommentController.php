<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, $postId)
    {
        $request->validate([
            'body' => 'required|string',
        ]);
    
        // Create the new comment
        $comment = new Comment();
        $comment->body = $request->body;
        $comment->user_id = auth()->id(); // Assuming the user is authenticated
        $comment->post_id = $postId;
        $comment->save();
    
        // Return the new comment data as a JSON response
        return response()->json([
            'user' => [
                'name' => auth()->user()->name
            ],
            'body' => $comment->body,
            'created_at' => $comment->created_at->diffForHumans()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // Update an existing comment
    public function update(Request $request, Post $post, Comment $comment)
    {
        // Check if the authenticated user is the owner of the comment
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate the request data
        $request->validate([
            'body' => 'required|string|max:255', // Adjust validation as needed
        ]);

        // Update the comment
        $comment->body = $request->body;
        $comment->updated_at = now(); // Update timestamp
        $comment->save();

        return response()->json([
            'body' => $comment->body,
            'updated_at' => $comment->updated_at->diffForHumans(), // Format as needed
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    // Delete an existing comment
    public function destroy($postId, $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        
        // Optional: Check if the authenticated user is the owner of the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        // Return a response indicating success
        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}
