# News Blog App for Laravel

## Installation
install Laravel 

## Install Bootstrap 
```bash
composer require laravel/ui --dev
php artisan ui bootstrap --auth
```

## Install the bootstrap icons library.
```bash
npm install bootstrap-icons --save-dev
```

## Install the Livewide assets.
```bash
php artisan livewire:publish --assets
```


## Install Package
```bash
composer require 976-tuna/news
php artisan vendor:publish --provider="Tuna976\NEWS\NEWSServiceProvider" --tag="news-files"
```

## Integration

### Add the following to your user model:
```html
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
```

## Inside your project, open the file resources\sass\app.scss and add :
```html
@import 'bootstrap-icons/font/bootstrap-icons.css';
```