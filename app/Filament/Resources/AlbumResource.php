<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlbumResource\Pages;
use App\Filament\Resources\AlbumResource\RelationManagers;
use App\Models\Album;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AlbumResource extends Resource
{
    protected static ?string $model = Album::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Galeri';

    protected static ?string $navigationLabel = 'Album Foto';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn() => Auth::user()->id)
                    ->required()
                    ->dehydrated(),
                Forms\Components\TextInput::make('title')
                    ->label('Nama Album')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('album_type')
                    ->label('tipe album')
                    ->options([
                        'foto' => 'Foto',
                        'video' => 'Video',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('cover')
                    ->label('Cover')
                    ->directory('album')
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth('300')
                    ->imageResizeTargetHeight('300')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'lg' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('cover')
                        ->label('Cover')
                        ->height(200)
                        ->width('100%'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('title')
                            ->label('Nama Album')
                            ->searchable()
                            ->weight('bold'),
                        Tables\Columns\TextColumn::make('user.name')
                            ->label('Author')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('album_type')
                            ->label('Tipe Album')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'foto' => 'success',
                                'video' => 'warning',
                            }),
                    ])->space(1)->extraAttributes(['class' => 'p-2']),
                ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Tambah/Edit'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlbums::route('/'),
            'create' => Pages\CreateAlbum::route('/create'),
            'edit' => Pages\EditAlbum::route('/{record}/edit'),
        ];
    }
}
