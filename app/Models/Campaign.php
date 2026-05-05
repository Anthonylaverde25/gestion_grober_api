<?php

namespace App\Models;

use App\Core\Infrastructure\Persistence\Eloquent\Traits\HasActiveCompany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasUuids, HasActiveCompany;

    protected $fillable = [
        'id',
        'codigo',
        'machine_id',
        'article_id',
        'client_id',
        'operator_id',
        'status',
        'started_at',
        'finished_at',
        'eficiencia_promedio',
        'total_yield_records',
        'observaciones',
        'snapshot_data',
        'company_id',
    ];

    protected $casts = [
        'snapshot_data' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lineYields()
    {
        return $this->hasMany(LineYield::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
