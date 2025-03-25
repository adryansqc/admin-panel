<?php

namespace App\Observers;

use App\Models\Foto;
use Illuminate\Support\Facades\Storage;

class FotoObserver
{
    public function created(Foto $foto): void
    {
        //
    }

    public function updated(Foto $foto): void
    {
        if ($foto->isDirty('image')) {
            $oldImages = $foto->getOriginal('image') ?? [];
            $newImages = $foto->image ?? [];
            $imagesToDelete = array_diff($oldImages, $newImages);

            foreach ($imagesToDelete as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }
    }

    public function deleted(Foto $foto): void
    {
        if ($foto->image) {
            foreach ($foto->image as $image) {
                Storage::disk('public')->delete($image);
            }
        }
    }

    public function restored(Foto $foto): void
    {
        //
    }

    public function forceDeleted(Foto $foto): void
    {
        //
    }
}
