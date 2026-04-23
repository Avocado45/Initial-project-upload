<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CitationController;

//Route::post('/citations/fetch', [CitationController::class, 'fetch'])->name('citations.fetch');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    Route::post('/products/{product}/comments', [CommentController::class, 'store'])
        ->name('products.comments.store');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])
        ->name('comments.edit');

    Route::put('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');
});

Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show');
    
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');    

Route::get('/users/{user}', [UserController::class, 'show'])
    ->name('users.show');

Route::get('/categories/{category}', [CategoryController::class, 'show'])
    ->name('categories.show');


require __DIR__.'/auth.php';
