<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void {}

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        if ($post->isDirty('images') && $post->getOriginal('images')) {
            Storage::disk('public')->delete($post->getOriginal('images'));
        }

        if ($post->isDirty('content_body')) {
            $oldContent = $post->getOriginal('content_body');
            $newContent = $post->content_body;

            preg_match_all('/src="([^"]*\/conten_body\/[^"]+)"/', $oldContent, $oldMatches);
            preg_match_all('/src="([^"]*\/conten_body\/[^"]+)"/', $newContent, $newMatches);
            $removedImages = array_diff($oldMatches[1] ?? [], $newMatches[1] ?? []);

            foreach ($removedImages as $image) {
                $path = str_replace('/storage/', '', parse_url($image, PHP_URL_PATH));
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }

    public function deleted(Post $post): void
    {
        if ($post->images) {
            Storage::disk('public')->delete($post->images);
        }

        preg_match_all('/src="([^"]*\/conten_body\/[^"]+)"/', $post->content_body, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $image) {
                $path = str_replace('/storage/', '', parse_url($image, PHP_URL_PATH));
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
