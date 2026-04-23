<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $comment = $product->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $data['body'],
        ]);

        $owner = $product->user;
        if ($owner && $owner->id !== $request->user()->id) {
            $owner->alerts()->create([
                'message' => $request->user()->name .
                            ' commented on your product "' .
                            $product->product_name . '"',
                'link'    => route('products.show', $product),
        ]);
    }

        if ($request->ajax()) {
        $html = view('comments._comment', compact('comment'))->render();
        
        return response()->json(['html' => $html]);

    }

        return back();
    }

    public function destroy(Request $request, Comment $comment)
    {
        if ($request->user()->id !== $comment->user_id && !$request->user()->isAdmin()) {
            abort(403, 'Insufficient permission to delete this comment.');
        }

        $comment->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function edit(Request $request, Comment $comment)
{
    
    if ($request->user()->id !== $comment->user_id && !$request->user()->isAdmin()) {
        abort(403, 'You are not allowed to edit this comment.');
    }

    $comment->load('product'); 

    return view('comments.edit', compact('comment'));
}

public function update(Request $request, Comment $comment)
{
    if ($request->user()->id !== $comment->user_id && !$request->user()->isAdmin()) {
        abort(403, 'You are not allowed to update this comment.');
    }

    $data = $request->validate([
        'body' => 'required|string|max:2000',
    ]);

    $comment->update([
        'body' => $data['body'],
    ]);

    if ($comment->commentable_type === Product::class && $comment->commentable_id) {
        return redirect()
            ->route('products.show', $comment->commentable_id)
            ->with('status', 'Comment updated.');
    }

    return redirect()
        ->route('products.index')
        ->with('status', 'Comment updated.');
}




}