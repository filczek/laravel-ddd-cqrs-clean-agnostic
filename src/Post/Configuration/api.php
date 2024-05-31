<?php

declare(strict_types=1);

namespace DDD\Post\Configuration;

use DDD\Post\Interface\Api\CreatePost;
use DDD\Post\Interface\Api\DeletePost;
use DDD\Post\Interface\Api\GetPost;
use DDD\Post\Interface\Api\ListPosts;
use DDD\Post\Interface\Api\PublishPost;
use DDD\Post\Interface\Api\UpdatePost;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/v1/posts'], function () {
    Route::post('/', CreatePost::class);
    Route::get('/', ListPosts::class);
    Route::get('/{id}', GetPost::class);
    Route::post('/{id}/publish', PublishPost::class);
    Route::patch('/{id}',  UpdatePost::class);
    Route::delete('/{id}', DeletePost::class);
});
