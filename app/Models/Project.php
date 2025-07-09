<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class Project extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'work_type_id',
        'proj_number',
        'proj_name',
        'proj_work_type',
        'proj_leader',
        'proj_start_date',
        'proj_due_date',
        'proj_finished_date',
        'proj_notes',
        'proj_progress',
        'proj_status',
        'proj_hold_message',
        'proj_delayed_message',
        'proj_cancel_message'
    ];

    protected $fillable = [
        'work_type_id',
        'proj_number',
        'proj_name',
        'proj_customer',
        'proj_work_type',
        'proj_leader',
        'proj_start_date',
        'proj_due_date',
        'proj_finished_date',
        'proj_notes',
        'proj_progress',
        'proj_status',
        'proj_hold_message',
        'proj_delayed_message',
        'proj_cancel_message'
    ];

    public function project_survey(): HasOne
    {
        return $this->hasOne(Project_survey::class);
    }

    public function project_offer(): HasOne
    {
        return $this->hasOne(Project_offer::class);
    }

    public function work_type(): BelongsTo
    {
        return $this->belongsTo(Work_type::class)->withDefault(['work_name' => null]);
    }
}
