<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'stock' => $this->stock,
            'discount' => $this->pivot->discount,
            'count' => $this->pivot->count,
            'max_count' => $this->stock + $this->pivot->count,
            'cost' => $this->pivot->cost,
            'price' => $this->price,
        ];
    }
}
