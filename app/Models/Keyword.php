<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Keyword extends Model
{
    protected $fillable=[
        'keyword',
        'user_id'
    ];


    public function user():BelongsTo{
        return $this->BelongsTo(User::class);
    }

    public function articles():BelongsToMany{
        return $this->BelongsToMany(Article::class);
    }

}
