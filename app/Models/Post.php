<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $author_name
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $content
 * @property string|null $quote
 * @property string $category
 * @property array|null $tags
 * @property string|null $media_path
 * @property string $media_type
 * @property string $status
 * @property int $views
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @method static \Illuminate\Database\Eloquent\Builder|Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Post withTrashed()
 */
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
        'views',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',              // Automatically encodes/decodes JSON to PHP array
            'published_at' => 'datetime',
            'deleted_at' => 'datetime',
            'views' => 'integer',
        ];
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function rootComments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }


    // Relationship to User
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
