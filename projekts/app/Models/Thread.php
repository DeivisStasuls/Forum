<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str facade

class Thread extends Model
{
    use HasFactory;

    protected $guarded = [];

    // --- Boot Method for Slug Generation ---
    protected static function boot()
    {
        parent::boot();

        // Automatically generate slug before saving (creating)
        static::creating(function ($thread) {
            // Ensure slug is generated from title if it's missing
            if (empty($thread->slug) && !empty($thread->title)) {
                $thread->slug = Str::slug($thread->title);
            }
        });

        // When a thread is deleted, delete its associated posts
        static::deleting(function ($thread) {
            $thread->posts()->delete();
        });
    }

    // --- Relationships ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function posts()
    {
        // One thread has many posts (replies)
        return $this->hasMany(Post::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(User::class, 'subscriptions')->withTimestamps();
    }

    // Alias for subscriptions
    public function subscribers()
    {
        return $this->subscriptions();
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    // --- Accessor for Route Binding ---
    public function getRouteKeyName()
    {
        return 'slug';
    }
}