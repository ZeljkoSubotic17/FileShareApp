<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->is_admin !== 1) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $users = User::where('company_id', Auth::user()->company_id)->get();
        $user = Auth::user();

        return view('users.index', compact('users', 'user'));
    }
}
