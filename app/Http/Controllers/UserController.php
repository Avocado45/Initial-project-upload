<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        
        $products = $user->products()
            ->withCount('comments')
            ->latest()
            ->get();

        
        $comments = $user->comments()
            ->with('product')
            ->latest()
            ->get();

        return view('users.show', compact('user', 'products', 'comments'));
    }
}
