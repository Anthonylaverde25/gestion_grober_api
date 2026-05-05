<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function modules()
    {
        return $this->belongsToMany(Module::class)->withPivot('permissions')->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user');
    }
}
