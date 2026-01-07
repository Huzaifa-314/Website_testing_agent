<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['user_id', 'website_id', 'summary', 'generated_at'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function website(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
