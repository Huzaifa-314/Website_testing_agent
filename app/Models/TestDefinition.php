<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestDefinition extends Model
{
    protected $fillable = ['website_id', 'description', 'test_scope'];

    public function website(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function testCases(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TestCase::class);
    }
}
