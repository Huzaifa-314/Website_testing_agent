<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\TestRun;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show report for a specific website.
     */
    public function index(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }

        // Fetch latest test runs via test definitions -> test cases -> test runs
        // Or simpler: Get all test definitions for this website
        $testDefinitions = $website->testDefinitions()->with(['testCases.testRuns' => function($query) {
            $query->latest();
        }])->get();

        return view('reports.index', compact('website', 'testDefinitions'));
    }

    /**
     * Show details of a specific test run.
     */
    public function show(Request $request, TestRun $testRun)
    {
         if ($testRun->testCase->testDefinition->website->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }
        
        return view('reports.show', compact('testRun'));
    }
}
