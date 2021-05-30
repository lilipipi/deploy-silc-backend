<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    // protected $dateFormat = 'U';

    use HasFactory;
    protected $fillable = [
        'assetType', 'assetTitle', 'location', 'investmentGoal',
        'investmentTerm', 'minInvestmentAmount', 'interestRate', 'investmentReceived', 'image', 'assetInfo',
        'isVerified'
    ];

    public function AssetImages()
    {
        return $this->hasMany(AssetImage::class);
    }

    public function EventHistories()
    {
        return $this->hasMany(EventHistory::class);
    }
}