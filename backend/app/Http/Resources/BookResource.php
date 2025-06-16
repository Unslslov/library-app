<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'title' => $this->title,
            'author' => $this->author,
            'genre' => $this->genre,
            'publisher' => $this->publisher,
            'year_published' => $this->year_published,
            'available_copies' => $this->available_copies,
            'total_copies' => $this->total_copies,
            'description' => $this->description,
            'image_url' => $this->image_url,
        ];
    }
}
