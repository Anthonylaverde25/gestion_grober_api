<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Infrastructure\Persistence\Eloquent\Traits\HasActiveCompany;

class Furnace extends Model
{
    use HasUuids, SoftDeletes, HasActiveCompany;

    protected $fillable = ['company_id', 'glass_type_id', 'name', 'max_capacity_tons', 'current_status'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function glassType()
    {
        return $this->belongsTo(GlassType::class);
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }
}
