<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $websites = request()->user()->websites()->latest()->get();
        return view('websites.index', compact('websites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('websites.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url|max:255',
        ]);

        $request->user()->websites()->create([
            'url' => $validated['url'],
            'status' => 'pending',
        ]);

        return redirect()->route('websites.index')->with('success', 'Website added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Website $website)
    {
        if ($website->user_id !== request()->user()->id) {
            abort(403);
        }
        return view('websites.show', compact('website'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
