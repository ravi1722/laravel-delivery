<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    protected $fillable = ['body', 'rating', 'reviewer_name', 'reviewable_id', 'reviewable_type'];

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }
}
