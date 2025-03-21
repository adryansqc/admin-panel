<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;

class ViewAnnouncement extends ViewRecord
{
    protected static string $resource = AnnouncementResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('user.name')
                                    ->label('Penulis')
                                    ->icon('heroicon-o-user')
                                    ->color('primary'),
                                Infolists\Components\TextEntry::make('title')
                                    ->label('Judul Pengumuman')
                                    ->icon('heroicon-o-document-text')
                                    ->color('success')
                                    ->weight('bold'),
                            ]),
                        Infolists\Components\TextEntry::make('content')
                            ->label('Isi Pengumuman')
                            ->html()
                            ->prose()
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->icon('heroicon-o-clock')
                                    ->dateTime('d F Y H:i:s'),
                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label('Diperbarui Pada')
                                    ->icon('heroicon-o-arrow-path')
                                    ->dateTime('d F Y H:i:s'),
                            ]),
                    ])
                    ->collapsible()
            ]);
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->title;
    }
}
