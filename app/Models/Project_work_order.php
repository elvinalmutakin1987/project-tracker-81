<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Project_work_order extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $auditInclude = [
        'project_id',
        'user_id',
        'projwo_number',
        'projwo_started_at',
        'projwo_finished_at',
        'projwo_status',
        'projwo_hold_message',
        'projwo_revisi_message',
        'projwo_cancel_message',
        ''
    ];
}
