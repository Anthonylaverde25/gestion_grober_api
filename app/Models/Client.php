<?php

namespace App\Models;

use App\Core\Infrastructure\Persistence\Eloquent\Traits\HasActiveCompany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasUuids, HasActiveCompany;

    protected $fillable = [
        'commercial_name',
        'business_name',
        'tax_id',
        'technical_contact',
        'email',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
