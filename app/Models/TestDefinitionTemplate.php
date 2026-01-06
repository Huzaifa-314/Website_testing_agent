<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestDefinitionTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'test_scope',
        'example_description',
        'example_steps',
        'is_system',
        'user_id',
    ];

    protected $casts = [
        'example_steps' => 'array',
        'is_system' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
