<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Infrastructure\Persistence\Eloquent\Traits\HasActiveCompany;

class Article extends Model
{
    use HasUuids, SoftDeletes, HasActiveCompany;

    protected $table = 'articles';

    protected $fillable = ['company_id', 'client_id', 'name'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function currentMachines()
    {
        return $this->hasMany(Machine::class, 'current_article_id');
    }
}
