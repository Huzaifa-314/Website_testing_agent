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
        $websites = request()->user()->websites()
            ->withCount(['testDefinitions', 'reports'])
            ->latest()
            ->get();
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
        
        // Load relationships for the view
        $website->load([
            'testDefinitions.testCases.testRuns' => function ($query) {
                $query->latest()->limit(10);
            }
        ]);
        
        return view('websites.show', compact('website'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Website $website)
    {
        if ($website->user_id !== request()->user()->id) {
            abort(403);
        }
        
        return view('websites.edit', compact('website'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Website $website)
    {
        if ($website->user_id !== request()->user()->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'url' => 'required|url|max:255',
            'status' => 'required|in:active,inactive,error,pending',
        ]);

        $website->update($validated);

        return redirect()->route('websites.show', $website)->with('success', 'Website updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Website $website)
    {
        if ($website->user_id !== request()->user()->id) {
            abort(403);
        }
        
        $website->delete();

        return redirect()->route('websites.index')->with('success', 'Website deleted successfully.');
    }
}
