<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consortium extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = ['name', 'is_active'];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
