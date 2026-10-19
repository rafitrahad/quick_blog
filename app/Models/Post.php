<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'author_name',
        'title',
        'slug',
        'description',
        'content',
        'quote',
        'category',
        'tags',
        'media_path',
        'media_type',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',              // Automatically encodes/decodes JSON to PHP array
            'published_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }


    // Relationship to User
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
