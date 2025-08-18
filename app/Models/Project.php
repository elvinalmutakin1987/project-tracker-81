<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class Project extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $auditInclude = [
        'work_type_id',
        'customer_id',
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
        'proj_pic',
        'proj_customer',
        'proj_type',
        'proj_hold_message',
        'proj_delayed_message',
        'proj_cancel_message',
        'proj_denah',
        'proj_shop',
        'proj_sld',
        'proj_rab',
        'proj_personil',
        'proj_schedule',
        'proj_phone',
        'proj_email',
    ];

    protected $fillable = [
        'work_type_id',
        'customer_id',
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
        'proj_pic',
        'proj_customer',
        'proj_type',
        'proj_hold_message',
        'proj_delayed_message',
        'proj_cancel_message',
        'proj_denah',
        'proj_shop',
        'proj_sld',
        'proj_rab',
        'proj_personil',
        'proj_schedule',
        'proj_phone',
        'proj_email',
    ];

    public function project_survey(): HasOne
    {
        return $this->hasOne(Project_survey::class);
    }

    public function project_offer(): HasOne
    {
        return $this->hasOne(Project_offer::class);
    }

    public function project_sales_order(): HasOne
    {
        return $this->hasOne(Project_sales_order::class);
    }

    public function project_invoice_dp(): HasOne
    {
        return $this->hasOne(Project_invoice_dp::class);
    }

    public function project_work_order(): HasOne
    {
        return $this->hasOne(Project_work_order::class);
    }

    public function work_type(): BelongsTo
    {
        return $this->belongsTo(Work_type::class)->withDefault(['work_name' => null]);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withDefault(['cust_name' => null]);
    }

    public function project_brand(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'project_brands');
    }
}
