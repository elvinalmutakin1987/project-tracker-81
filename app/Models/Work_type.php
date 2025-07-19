<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Work_type extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $auditInclude = [
        'work_name',
    ];

    protected $fillable = [
        'work_name',
    ];

    public function project(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
