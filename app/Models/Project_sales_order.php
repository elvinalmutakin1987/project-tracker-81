<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Project_sales_order extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $auditInclude = [
        'project_id',
        'user_id',
        'projso_number',
        'projso_started_at',
        'projso_finished_at',
        'projso_grand_total',
        'projso_sales_order',
        'projso_so_number',
        'projso_po_number',
        'projso_status',
        'projso_sent_by',
        'projso_hold_message',
        'projso_revisi_message',
        'projso_cancel_message'
    ];

    protected $fillable = [
        'project_id',
        'user_id',
        'projso_number',
        'projso_started_at',
        'projso_finished_at',
        'projso_grand_total',
        'projso_sales_order',
        'projso_so_number',
        'projso_po_number',
        'projso_status',
        'projso_sent_by',
        'projso_hold_message',
        'projso_revisi_message',
        'projso_cancel_message'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class)->withDefault(['proj_number' => null]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault(['username' => null]);
    }

    public function sales_order(): HasMany
    {
        return $this->hasMany(Sales_order::class);
    }
}
