<?php

declare(strict_types=1);

use App\Orchid\Screens\PostListScreen;
use App\Orchid\Screens\UserListScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

Route::screen('users', UserListScreen::class)
    ->name('platform.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Users'));

Route::screen('posts', PostListScreen::class)
    ->name('platform.posts')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Posts'));
