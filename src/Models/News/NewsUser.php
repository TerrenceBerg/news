<?php

namespace App\Models\News;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tuna976\NEWS\Models\Post;
use Tuna976\NEWS\Models\Comment;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class NewsUser extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Added role field
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // Role-based authentication methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    public function isAuthor(): bool
    {
        return $this->role === 'author' || $this->isAdmin();
    }
    
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
    
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
