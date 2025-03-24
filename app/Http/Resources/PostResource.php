<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->user->name,
            'category_id' => $this->category_id,
            'category' => $this->category->name,
            'judul_berita' => $this->judul_berita,
            'tanggal' => $this->tanggal,
            'status' => $this->status,
            'jumlah_view' => $this->jumlah_view,
            'images' => $this->images,
            'images_caption' => $this->images_caption,
            'content_body' => $this->content_body,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at
        ];
    }
}
