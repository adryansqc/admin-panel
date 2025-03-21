<?php

namespace App\Observers;

use App\Models\Announcement;
use Illuminate\Support\Facades\Storage;

class AnnouncementObserver
{
    public function created(Announcement $announcement): void {}

    public function updated(Announcement $announcement): void
    {
        if ($announcement->isDirty('content')) {
            $oldContent = $announcement->getOriginal('content');
            $newContent = $announcement->content;

            preg_match_all('/src="([^"]*\/content\/[^"]+)"/', $oldContent, $oldMatches);
            preg_match_all('/src="([^"]*\/content\/[^"]+)"/', $newContent, $newMatches);
            $removedImages = array_diff($oldMatches[1] ?? [], $newMatches[1] ?? []);

            foreach ($removedImages as $image) {
                $path = str_replace('/storage/', '', parse_url($image, PHP_URL_PATH));
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }

    public function deleted(Announcement $announcement): void
    {
        preg_match_all('/src="([^"]*\/content\/[^"]+)"/', $announcement->content, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $image) {
                $path = str_replace('/storage/', '', parse_url($image, PHP_URL_PATH));
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }

    public function restored(Announcement $announcement): void
    {
        //
    }

    public function forceDeleted(Announcement $announcement): void
    {
        //
    }
}
