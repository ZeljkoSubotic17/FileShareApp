<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\InviteEmail;
use Illuminate\Support\Str;

class InviteController extends Controller
{
    public function sendInvite(Request $request)
    {
        // Validacija podataka
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        $token = Str::random(32);

        $invite = Invite::create([
            'email' => $request->email,
            'invited_by' => Auth::user()->id,
            'token' => $token
        ]);


        // Send email
        try {
            Mail::to($request->email)->send(new InviteEmail($invite));
            return response()->json(['message' => 'Invite sent successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send invite'], 500);
        }
    }
}
