<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Company::with('admin')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:companies',
            'pib' => 'required|unique:companies|max_digits:13',
            'address' => 'required|unique:companies',
        ]);

        $companyData = [
            'name' => $request->name,
            'pib' => $request->pib,
            'address' => $request->address,
            'admin_id' => Auth::user()->id
        ];

        $company = Company::create($companyData);

        $user = Auth::user();
        $user->company_id = $company->id;
        $user->is_admin = true;
        $user->save();

        return response()->json([
            'company' => $company,
            'url' => route('companies.show', ['company' => $company->id])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Company::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());

        return response()->json($company, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Company::destroy($id);

        return response()->json(null, 204);
    }
}
