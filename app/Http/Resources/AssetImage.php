<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetImage extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'imageAddress' => $this->imageAddress,
            'asset_id' => $this->asset_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}