<?php

namespace App\Filament\Resources\VideoResource\Pages;

use App\Filament\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateVideo extends CreateRecord
{
    protected static string $resource = VideoResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Buat Video';
    }
}
