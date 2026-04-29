<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlassType extends Model
{
    protected $fillable = ['name'];

    public function furnaces()
    {
        return $this->hasMany(Furnace::class);
    }
}
