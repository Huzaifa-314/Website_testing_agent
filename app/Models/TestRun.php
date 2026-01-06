<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestRun extends Model
{
    protected $fillable = ['test_case_id', 'status', 'current_step', 'total_steps', 'result', 'logs', 'executed_at'];

    protected $casts = [
        'logs' => 'array',
        'executed_at' => 'datetime',
    ];

    public function testCase(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TestCase::class);
    }
}
