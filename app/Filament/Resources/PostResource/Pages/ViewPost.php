<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Illuminate\Contracts\Support\Htmlable;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('user.name')
                                    ->label('Author')
                                    ->icon('heroicon-o-user')
                                    ->color('primary'),
                                Infolists\Components\TextEntry::make('category.name')
                                    ->label('Kategori')
                                    ->icon('heroicon-o-tag')
                                    ->color('success'),
                            ]),
                        Infolists\Components\TextEntry::make('judul_berita')
                            ->label('Judul Berita')
                            ->icon('heroicon-o-document-text')
                            ->size('lg')
                            ->weight('bold')
                            ->columnSpanFull(),
                        Infolists\Components\ImageEntry::make('images')
                            ->label('Gambar')
                            ->placeholder('Gambar tidak ada')
                            ->disk('public')
                            ->visibility(fn($state) => $state !== null)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('images_caption')
                            ->label('Caption Gambar')
                            ->size('sm')
                            ->color('gray')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('content_body')
                            ->label('Isi Berita')
                            ->html()
                            ->prose()
                            ->columnSpanFull(),
                        Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('tanggal')
                                    ->label('Tanggal')
                                    ->icon('heroicon-o-calendar')
                                    ->date(),
                                Infolists\Components\TextEntry::make('status')
                                    ->label('Status')
                                    ->icon('heroicon-o-signal')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'draft' => 'gray',
                                        'publish' => 'success',
                                    }),
                                Infolists\Components\TextEntry::make('jumlah_view')
                                    ->label('Jumlah View')
                                    ->icon('heroicon-o-eye')
                                    ->numeric(),
                            ]),
                    ])
                    ->collapsible()
            ]);
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->judul_berita;
    }
}
