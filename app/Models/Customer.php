<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Customer extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'cust_name',
        'cust_address',
        'cust_director_name',
        'cust_contact_number',
        'cust_email',
        'cust_type',
        'cust_active'
    ];

    protected $fillable = [
        'cust_name',
        'cust_address',
        'cust_director_name',
        'cust_contact_number',
        'cust_email',
        'cust_type',
        'cust_active'
    ];

    public function project_survey(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
