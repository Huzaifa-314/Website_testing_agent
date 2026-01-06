<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Website;
use App\Models\TestDefinition;

use App\Services\MockAiTestGenerator;

class TestDefinitionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $websiteId = $request->query('website_id');
        if (! $websiteId) {
            abort(404);
        }

        $website = Website::findOrFail($websiteId);

        if ($website->user_id !== $request->user()->id) {
            abort(403);
        }

        return view('test_definitions.create', compact('website'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, MockAiTestGenerator $generator)
    {
        $validated = $request->validate([
            'website_id' => 'required|exists:websites,id',
            'description' => 'required|string',
            'test_scope' => 'required|string',
        ]);

        $website = Website::findOrFail($validated['website_id']);

        if ($website->user_id !== $request->user()->id) {
            abort(403);
        }

        $definition = $website->testDefinitions()->create([
            'description' => $validated['description'],
            'test_scope' => $validated['test_scope'],
        ]);

        // Mock AI Generation
        $steps = $generator->generate($definition->description, $definition->test_scope);

        $definition->testCases()->create([
            'steps' => $steps,
            'status' => 'generated',
            'expected_result' => 'Operation successful',
        ]);

        return redirect()->route('websites.show', $website)->with('success', 'Test definition saved and mock test case generated.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    /**
     * Run the test execution.
     */
    public function run(Request $request, string $id, \App\Services\TestExecutionService $executor)
    {
        $testDefinition = TestDefinition::findOrFail($id);
        
        if ($testDefinition->website->user_id !== $request->user()->id) {
            abort(403);
        }

        // Run all associated test cases (for now just one usually)
        foreach ($testDefinition->testCases as $testCase) {
             $executor->run($testCase);
             $testCase->update(['status' => 'completed']);
        }

        return redirect()->route('websites.show', $testDefinition->website_id)->with('success', 'Test execution completed.');
    }
}
