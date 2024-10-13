<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

Route::resource('/', PostController::class);
Route::get('/', [PostController::class,'index'])->name('dashboard');

Route::put('/posts/{id}', [PostController::class, 'update'])->name('post.update');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('post.destroy');

// Routes for comments
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::put('/posts/{post}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

Route::view('/about', 'about')->name('about');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
