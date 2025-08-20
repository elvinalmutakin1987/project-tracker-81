<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Work_order extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $auditInclude = [
        'project_id',
        'project_work_order_id',
        'user_id',
        'wo_number',
        'wo_date',
        'wo_print_count',
        'wo_started_at',
        'wo_finished_at',
        'created_by',
        'checked1_by',
        'checked2_by',
        'checked3_by',
        'approved_by',
    ];

    protected $fillable = [
        'project_id',
        'project_work_order_id',
        'user_id',
        'wo_number',
        'wo_date',
        'wo_print_count',
        'wo_started_at',
        'wo_finished_at',
        'created_by',
        'checked1_by',
        'checked2_by',
        'checked3_by',
        'approved_by',
    ];
}
