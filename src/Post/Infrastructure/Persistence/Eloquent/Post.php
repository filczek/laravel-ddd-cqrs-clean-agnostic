<?php

namespace DDD\Post\Infrastructure\Persistence\Eloquent;

use DDD\Post\Infrastructure\Persistence\PostSnapshot;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id',
        'version',
        'state',
        'title',
        'content',
        'created_at',
        'published_at',
        'deleted_at'
    ];

    protected $guarded = [];

    public function toSnapshot(): PostSnapshot
    {
        return new PostSnapshot(
            id: $this->id,
            version: $this->version,
            state: $this->state,
            title: $this->title,
            content: $this->content,
            created_at: $this->created_at,
            published_at: $this->published_at,
            deleted_at: $this->deleted_at
        );
    }
}
