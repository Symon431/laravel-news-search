<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    //
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable=[
        'title',
        'user_id',
        'summary',
        'source',
        'url',
        'image_url',
        'keyword_id',
        'published_at'
    ];
}
