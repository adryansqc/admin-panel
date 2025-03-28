<?php

namespace App\Providers;

use App\Models\Album;
use App\Models\Announcement;
use App\Models\Foto;
use App\Models\Post;
use App\Models\User;
use App\Observers\AlbumObserver;
use App\Observers\AnnouncementObserver;
use App\Observers\FotoObserver;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Post::observe(PostObserver::class);
        Album::observe(AlbumObserver::class);
        Announcement::observe(AnnouncementObserver::class);
        Foto::observe(FotoObserver::class);
    }
}
