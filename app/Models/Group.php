<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'name',
        'description',
        'is_private',
        'tsvectors'
    ];

    public function posts(): HasMany {
        return $this->hasMany(Post::class, "group_id");
    }
    
}
