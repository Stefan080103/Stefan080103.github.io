<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'avatar',
        'phone',
        'birth_date',
        'gender',
        'is_private',
        'followers_count',
        'following_count',
        'posts_count',
        'last_active_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'last_active_at' => 'datetime',
        'is_private' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Helper methods
    public function isFollowing(User $user)
    {
        return $this->following()
            ->where('following_id', $user->id)
            ->where('status', 'accepted')
            ->exists();
    }

    public function hasFollowRequest(User $user)
    {
        return $this->following()
            ->where('following_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function canMessageWith(User $user)
    {
        return $this->isFollowing($user) && $user->isFollowing($this);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=7C3AED&background=EDE9FE";
    }

    public function getFollowersCountAttribute($value)
    {
        return $this->followers()->where('status', 'accepted')->count();
    }

    public function getFollowingCountAttribute($value)
    {
        return $this->following()->where('status', 'accepted')->count();
    }

    public function getPostsCountAttribute($value)
    {
        return $this->posts()->count();
    }

    public function updateLastActivity()
    {
        $this->update(['last_active_at' => now()]);
    }

    public function isOnline()
    {
        return $this->last_active_at && $this->last_active_at->diffInMinutes(now()) < 5;
    }

    public function getStatusAttribute()
    {
        if ($this->isOnline()) {
            return 'online';
        }
        
        if ($this->last_active_at && $this->last_active_at->diffInHours(now()) < 24) {
            return 'recent';
        }
        
        return 'offline';
    }
}
