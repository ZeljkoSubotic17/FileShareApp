<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        $userCompanyId = auth()->user()->company_id;

        if ($userCompanyId !== (int) $id) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $company = Company::with('admin')->findOrFail($id);
        $user = Auth::user();

        return view('companies.show', compact('company', 'user'));
    }

    public function inviteUser(Request $request, Company $company)
    {
        $request->validate([
            'email' => 'required|email|unique:users'
        ]);

        // Create a new user with a random password
        $password = Str::random(8);
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($password),
            'company_id' => $company->id
        ]);

        // Send invite email (simplified)
        Log::info('Send invite email with link');

        return redirect()->route('companies.show', $company);
    }
}
