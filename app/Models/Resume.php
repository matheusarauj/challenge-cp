<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
    const JUNIOR_LEVEL = 'JUNIOR';
    const STAFF_LEVEL = 'STAFF';
    const SENIOR_LEVEL = 'SENIOR';

    const HIGHSCHOOL_SCHOLARSHIP = 'HIGHSCHOOL';
    const BACHELOR_SCHOLARSHIP = 'BACHELOR';
    const MASTER_SCHOLARSHIP = 'MASTER';

    protected $fillable = [
        'full_name',
        'description',
        'mail',
        'phone',
        'site',
        'active',
        'level',
        'scholarship',
        'tech_stack',
        'city_id',
        'registered_by',
        'deleted_by',
        'deleted_at'
    ];

    protected $table = 'resumes';

    public function City(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}
