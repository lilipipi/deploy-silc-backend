<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Asset extends JsonResource
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
            'id' => $this->id,
            'assetType' => $this->assetType,
            'assetTitle' => $this->assetTitle,
            'location' => $this->location,
            'investmentGoal' => (int) $this->investmentGoal,
            'investmentTerm' => $this->investmentTerm,
            'minInvestmentAmount' => (int) $this->minInvestmentAmount,
            'interestRate' => (int) $this->interestRate,
            'investmentReceived' => (int) $this->investmentReceived,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'isVerified' => (boolean) $this->isVerified,
            'assetInfo' => $this->assetInfo,
        ];
    }
}