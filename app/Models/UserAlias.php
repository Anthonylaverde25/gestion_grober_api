<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAlias extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'legajo',
        'is_active'
    ];

    /**
     * Get the user (Terminal) that owns the alias.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
