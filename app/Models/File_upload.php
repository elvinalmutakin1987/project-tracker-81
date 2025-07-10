<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class File_upload extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'file_doc_type',
        'file_table',
        'file_table_id',
        'file_directory',
        'file_name',
        'file_real_name',
        'file_ext',
        'file_link'
    ];

    protected $fillable = [
        'file_doc_type',
        'file_table',
        'file_table_id',
        'file_directory',
        'file_name',
        'file_real_name',
        'file_ext',
        'file_link'
    ];
}
