<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Project_invoice_dp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $auditInclude = [
        'project_id',
        'user_id',
        'projinvdp_number',
        'projinvdp_invoice',
        'projinvdp_invoice_number',
        'projinvdp_grand_total',
        'projinvdp_started_at',
        'projinvdp_finished_at',
        'projinvdp_status',
        'projinvdp_sent_by',
        'projinvdp_email_to',
        'projinvdp_wa_to',
        'projinvdp_send_at',
        'projinvdp_hold_message',
        'projinvdp_revisi_message',
        'projinvdp_cancel_message',
        'projinvdp_permit_wo',
        'projinvdp_create_wo',
        'projinvdp_permit_at',
        'permit_by',
        'created_wo_by'
    ];

    protected $fillable = [
        'project_id',
        'user_id',
        'projinvdp_number',
        'projinvdp_invoice',
        'projinvdp_invoice_number',
        'projinvdp_grand_total',
        'projinvdp_started_at',
        'projinvdp_finished_at',
        'projinvdp_status',
        'projinvdp_sent_by',
        'projinvdp_email_to',
        'projinvdp_wa_to',
        'projinvdp_send_at',
        'projinvdp_hold_message',
        'projinvdp_revisi_message',
        'projinvdp_cancel_message',
        'projinvdp_permit_wo',
        'projinvdp_create_wo',
        'projinvdp_permit_at',
        'permit_by',
        'created_wo_by'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class)->withDefault(['proj_number' => null]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault(['username' => null]);
    }

    public function permitby(): BelongsTo
    {
        return $this->belongsTo(User::class, 'permit_by', 'id')
            ->withDefault(['username' => null]);
    }

    public function createwoby(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_wo_by', 'id')
            ->withDefault(['username' => null]);
    }
}
