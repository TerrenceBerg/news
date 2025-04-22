<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\News\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\News\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\News\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\News\Admin\PostController as AdminPostController;
use App\Http\Controllers\News\Admin\TagController as AdminTagController;
use App\Http\Controllers\News\Admin\UserController as AdminUserController;
use App\Http\Controllers\News\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\News\HomeController;
use App\Http\Controllers\News\PostController;
use App\Http\Controllers\News\UserSubmissionController;
use app\Http\Middleware\EnsureUserHasRole;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
 
// Auth routes
    // Auth::routes();
// Public routes
    Route::prefix('news')->name('news.')->group(function () {
    // Post routes 
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
        Route::get('/category/{category:slug}', [PostController::class, 'byCategory'])->name('posts.category');
        Route::get('/tag/{tag:slug}', [PostController::class, 'byTag'])->name('posts.tag');
        Route::get('/search', [PostController::class, 'search'])->name('posts.search');
        Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

        // Public user posts route
            Route::get('/users/{userId}/posts', [UserSubmissionController::class, 'userPosts'])
                ->name('user.posts');

        // User submission routes
            Route::get('/submit', [UserSubmissionController::class, 'create'])->name('submissions.create');
            Route::post('/submit', [UserSubmissionController::class, 'store'])->name('submissions.store');
            Route::get('/submit/thank-you', [UserSubmissionController::class, 'thankYou'])->name('submissions.thank-you');
            Route::get('/my-posts', [UserSubmissionController::class, 'myPosts'])
                ->name('submissions.my-posts');
    });

// Admin routes - Use class directly instead of alias
    Route::/* middleware(['auth', EnsureUserHasRole::class.':admin'])-> */prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        // This route is for the admin dashboard
        // It is accessible only to users with the 'admin' role
        // The route is defined with the prefix 'admin' and the name 'admin.dashboard'
            Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Posts - Complete resource routes
            Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
            Route::get('/posts/create', [AdminPostController::class, 'create'])->name('posts.create');
            Route::post('/posts', [AdminPostController::class, 'store'])->name('posts.store');
            Route::get('/posts/{post}/edit', [AdminPostController::class, 'edit'])->name('posts.edit');
            Route::put('/posts/{post}', [AdminPostController::class, 'update'])->name('posts.update');
            Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');
        // Post images 
            Route::post('/posts/{post}/images', [AdminPostController::class, 'uploadImage'])->name('posts.upload-image');
            Route::delete('/posts/images/{postImage}', [AdminPostController::class, 'deleteImage'])->name('posts.delete-image');
            Route::post('/posts/images/reorder', [AdminPostController::class, 'reorderImages'])->name('posts.reorder-images');
            Route::put('/posts/images/{postImage}/alt', [AdminPostController::class, 'updateImageAlt'])->name('posts.update-image-alt');
        // Categories
            Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
            Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
        // Tags
            Route::get('/tags', [AdminTagController::class, 'index'])->name('tags.index');
            Route::get('/tags/create', [AdminTagController::class, 'create'])->name('tags.create');
            Route::get('/tags/{tag}/edit', [AdminTagController::class, 'edit'])->name('tags.edit');
        // Comments
            Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
            Route::get('/comments/{comment}/edit', [AdminCommentController::class, 'edit'])->name('comments.edit');
        // Users
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        // Submission management routes
            Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
            Route::get('/submissions/{id}/edit', [AdminSubmissionController::class, 'edit'])->name('submissions.edit');
            Route::put('/submissions/{id}', [AdminSubmissionController::class, 'update'])->name('submissions.update');
            Route::delete('/submissions/{id}', [AdminSubmissionController::class, 'destroy'])->name('submissions.destroy');
            Route::post('/submissions/{id}/publish', [AdminSubmissionController::class, 'publish'])->name('submissions.publish');
    });

