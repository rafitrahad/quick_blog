<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'author_name',
        'body',
        'reactions',
        'edited_at',
        'hidden_by',
    ];

    protected function casts(): array
    {
        return [
            'reactions'  => 'array',
            'edited_at'  => 'datetime',
            'hidden_by'  => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->oldest();
    }

    public function histories(): HasMany
    {
        return $this->hasMany(CommentHistory::class)->latest();
    }

    public function isHiddenFor(?int $userId): bool
    {
        if (!$userId || empty($this->hidden_by)) {
            return false;
        }

        return in_array($userId, (array) $this->hidden_by);
    }
}
