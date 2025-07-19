<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Brand extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'brand_name',
    ];

    protected $fillable = [
        'brand_name',
    ];

    public function project_brand(): HasMany
    {
        return $this->hasMany(Project_brand::class);
    }
}
