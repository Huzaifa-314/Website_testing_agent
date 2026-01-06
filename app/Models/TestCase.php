<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    protected $fillable = ['test_definition_id', 'steps', 'expected_result', 'status'];

    protected $casts = [
        'steps' => 'array',
    ];

    public function testDefinition(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TestDefinition::class);
    }

    public function testRuns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TestRun::class);
    }
}
