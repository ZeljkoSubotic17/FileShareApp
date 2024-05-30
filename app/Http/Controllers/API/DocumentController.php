<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Document::where('company_id', Auth::user()->company_id)->with('user')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'required|mimes:jpg,png,pdf|max:2048'
        ]);

        $path = $request->file('file')->store('storage/documents', 'public');

        $document = Document::create([
            'user_id' => Auth::id(),
            'company_id' => Auth::user()->company_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path
        ]);

        $document->load('user');

        return response()->json($document, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Document::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $document = Document::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|mimes:jpg,png,pdf|max:2048'
        ]);

        if ($request->hasFile('file')) {
            // Delete the old file if exists
            if ($document->file_path) {
                Storage::delete($document->file_path);
            }

            // Store the new file
            $path = $request->file('file')->store('documents');
            $document->file_path = $path;
        }

        // Update other fields
        $document->title = $request->title;
        $document->description = $request->description;
        $document->save();

        // Load the user relationship
        $document->load('user');

        return response()->json($document, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $document = Document::findOrFail($id);

        // Delete the file from storage
        Storage::delete($document->file_path);

        $document->delete();

        return response()->json(null, 204);
    }
}
