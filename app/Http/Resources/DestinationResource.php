<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'image' => $this->image ? asset('storage/'.$this->image) : null,
            'image_url' => $this->image_url,
            'pricing' => $this->pricing,
            'duration' => $this->duration,
            'formatted_pricing' => $this->formatted_pricing,
            'converted_pricing' => $this->converted_pricing,
            'published_at' => $this->published_at?->toIso8601String(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'average_rating' => $this->when($this->relationLoaded('reviews'), function () {
                return $this->reviews->avg('rating');
            }),
            'reviews_count' => $this->when($this->relationLoaded('reviews'), function () {
                return $this->reviews->count();
            }),
            'wishlisted' => $this->when(isset($this->wishlisted), $this->wishlisted),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
