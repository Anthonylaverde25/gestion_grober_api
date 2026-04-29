<?php

namespace App\Models;

use App\Core\Infrastructure\Persistence\Eloquent\Traits\HasActiveCompany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Machine extends Model
{
    use HasUuids, SoftDeletes, HasActiveCompany;

    protected $fillable = ['company_id', 'furnace_id', 'current_article_id', 'name', 'current_status'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function furnace()
    {
        return $this->belongsTo(Furnace::class);
    }

    public function currentArticle()
    {
        return $this->belongsTo(Article::class, 'current_article_id');
    }
}
