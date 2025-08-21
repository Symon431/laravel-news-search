<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    // ... existing profile and keyword routes ...

    // Keyword Routes (ensure these are still here and correct)
    Route::get('/keywords', [KeywordController::class, 'index'])->name('keywords.index');
    Route::post('/keywords', [KeywordController::class, 'store'])->name('keywords.store');
    Route::get('/keywords/{keyword}/edit', [KeywordController::class, 'edit'])->name('keywords.edit');
    Route::put('/keywords/{keyword}', [KeywordController::class, 'update'])->name('keywords.update');
    Route::delete('/keywords/{keyword}', [KeywordController::class, 'destroy'])->name('keywords.destroy');

    // --- Add these Article Routes ---
    // Route to display all fetched articles for the user
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

    // Route to display a form/button to trigger article fetching (e.g., "Search for News")
    Route::get('/articles/fetch', [ArticleController::class, 'showFetchForm'])->name('articles.fetch.form');

    // Route to handle the API call and save articles
    Route::post('/articles/fetch', [ArticleController::class, 'fetchAndSave'])->name('articles.fetch.process');

    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});
