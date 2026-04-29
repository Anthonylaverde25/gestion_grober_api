<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = ['consortium_id', 'manager_id', 'name', 'is_active'];

    public function consortium()
    {
        return $this->belongsTo(Consortium::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function furnaces()
    {
        return $this->hasMany(Furnace::class);
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user');
    }
}
