<?php

namespace App\Observers;

use App\Models\Album;
use Illuminate\Support\Facades\Storage;

class AlbumObserver
{
    public function created(Album $album): void
    {
        //
    }

    public function updated(Album $album): void
    {
        if ($album->isDirty('cover') && $album->getOriginal('cover')) {
            Storage::disk('public')->delete($album->getOriginal('cover'));
        }
    }

    public function deleted(Album $album): void
    {
        if ($album->cover) {
            Storage::disk('public')->delete($album->cover);
        }

        foreach ($album->fotos as $foto) {
            if ($foto->image) {
                foreach ($foto->image as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            $foto->delete();
        }
    }

    public function restored(Album $album): void
    {
        //
    }

    public function forceDeleted(Album $album): void
    {
        //
    }
}
