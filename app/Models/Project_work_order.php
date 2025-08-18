<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Project_work_order extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $auditInclude = [
        'project_id',
        'user_id',
        'projwo_number',
        'projwo_work_order',
        'projwo_started_at',
        'projwo_finished_at',
        'projwo_status',
        'projwo_hold_message',
        'projwo_revisi_message',
        'projwo_cancel_message',
        ''
    ];

    protected $fillable = [
        'project_id',
        'user_id',
        'projwo_number',
        'projwo_work_order',
        'projwo_started_at',
        'projwo_finished_at',
        'projwo_status',
        'projwo_hold_message',
        'projwo_revisi_message',
        'projwo_cancel_message',
        ''
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
