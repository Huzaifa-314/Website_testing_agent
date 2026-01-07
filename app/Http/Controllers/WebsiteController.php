<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->user()->websites()
            ->withCount(['testDefinitions', 'reports']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('url', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort_by to prevent SQL injection
        $allowedSorts = ['created_at', 'updated_at', 'url', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        
        // Validate sort_order
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';

        // Handle special sorting cases
        if ($sortBy === 'test_definitions_count') {
            $query->orderBy('test_definitions_count', $sortOrder);
        } elseif ($sortBy === 'reports_count') {
            $query->orderBy('reports_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $websites = $query->paginate(12)->withQueryString();

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

        $website = $request->user()->websites()->create([
            'url' => $validated['url'],
            'status' => 'pending',
        ]);

        // Log website creation
        ActivityLog::log(
            $request->user()->id,
            'create_website',
            "Created website: {$website->url}",
            ['website_id' => $website->id]
        );

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
