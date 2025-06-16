<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class LoanResource extends JsonResource
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
            'book' => [
                'id' => $this->book->id,
                'title' => $this->book->title,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'loaned_at' => $this->loaned_at
                ? Carbon::parse($this->loaned_at)->format('Y-m-d')
                : null,
            'reservation_id' => $this->reservation_id,
//            'returned_at' => $this->returned_at ? $this->returned_at->format('Y-m-d') : null,
        ];
    }
}
