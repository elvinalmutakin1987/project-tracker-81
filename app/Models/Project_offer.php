<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Project_offer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'project_id',
        'user_id',
        'projoff_started_at',
        'projoff_finished_at',
        'projoff_grand_total',
        'projoff_offer_number',
        'projoff_status',
        'projoff_sent_by',
        'projoff_hold_message',
        'projoff_revisi_message',
        'projoff_cancel_message'
    ];

    protected $fillable = [
        'project_id',
        'user_id',
        'projoff_started_at',
        'projoff_finished_at',
        'projoff_grand_total',
        'projoff_offer_number',
        'projoff_status',
        'projoff_sent_by',
        'projoff_hold_message',
        'projoff_revisi_message',
        'projoff_cancel_message'
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
