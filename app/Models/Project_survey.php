<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Project_survey extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'project_id',
        'user_id',
        'projsur_started_at',
        'projsur_finished_at',
        'projsur_denah',
        'projsur_shop',
        'projsur_sld',
        'projsur_rab',
        'projsur_personil',
        'projsur_schedule',
        'projsur_status',
        'projsur_hold_message',
        'projsur_cancel_message'
    ];

    protected $fillable = [
        'project_id',
        'user_id',
        'projsur_started_at',
        'projsur_finished_at',
        'projsur_denah',
        'projsur_shop',
        'projsur_sld',
        'projsur_rab',
        'projsur_personil',
        'projsur_schedule',
        'projsur_status',
        'projsur_hold_message',
        'projsur_cancel_message'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class)->withDefault(['proj_number' => null]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault(['username' => null]);
    }
}
