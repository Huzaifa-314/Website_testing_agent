<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = ['user_id', 'url', 'status'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function testDefinitions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TestDefinition::class);
    }

    public function reports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get all test runs for this website through test definitions and test cases.
     */
    public function getTestRuns()
    {
        return \App\Models\TestRun::whereHas('testCase.testDefinition', function ($query) {
            $query->where('website_id', $this->id);
        })->latest()->get();
    }

    /**
     * Update website status based on test result.
     * 
     * Status update logic:
     * - 'pending' → 'active' (on first successful test)
     * - 'pending' → 'error' (on first failed test)
     * - 'active' → 'error' (when test fails)
     * - 'error' → 'active' (when test passes, recovered)
     * - 'inactive' → unchanged (user manually paused testing)
     * 
     * @param string $testResult 'pass' or 'fail'
     * @return void
     */
    public function updateStatusFromTestResult(string $testResult): void
    {
        // Don't auto-update if user manually set to inactive
        if ($this->status === 'inactive') {
            return;
        }

        if ($testResult === 'pass') {
            // If pending or error, change to active (first success or recovered from error)
            if (in_array($this->status, ['pending', 'error'])) {
                $this->update(['status' => 'active']);
                $this->refresh();
            }
            // If already active, keep it active
        } elseif ($testResult === 'fail') {
            // If pending or active, change to error
            if (in_array($this->status, ['pending', 'active'])) {
                $this->update(['status' => 'error']);
                $this->refresh();
            }
            // If already error, keep it error
        }
    }
}
