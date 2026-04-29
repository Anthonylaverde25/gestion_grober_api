<?php

namespace App\Models;

use App\Core\Infrastructure\Persistence\Eloquent\Traits\HasActiveCompany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Extraction extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'machine_id',
        'article_id',
        'percentage',
        'measured_at',
        'is_active'
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'measured_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
