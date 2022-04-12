<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    protected $fillable = [
        'company',
        'description',
        'start',
        'end',
        'active',
        'resume_id',
        'city_id',
        'registerd_by',
        'deleted_by',
        'deleted_at'
    ];

    protected $table = 'experiences';

    public function City(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}
