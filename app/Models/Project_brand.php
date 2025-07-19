<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Project_brand extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $auditInclude = [
        'project_id',
        'brand_id'
    ];

    protected $fillable = [
        'project_id',
        'brand_id'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class)->withDefault(['proj_number' => null]);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class)->withDefault(['brand_name' => null]);
    }
}
