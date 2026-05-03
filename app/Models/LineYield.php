<?php

namespace App\Models;

use App\Core\Infrastructure\Persistence\Eloquent\Traits\HasActiveCompany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LineYield extends Model
{
    use HasUuids, HasActiveCompany;

    protected $fillable = [
        'campaign_id',
        'user_alias_id',
        'forming_yield',
        'packing_yield',
        'recorded_at',
        'notes',
        'company_id',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function userAlias()
    {
        return $this->belongsTo(UserAlias::class, 'user_alias_id');
    }
}
