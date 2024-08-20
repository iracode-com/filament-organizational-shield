<?php

namespace IracodeCom\FilamentOrganizationalShield\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'fullname',
        'mobile',
        'tel',
        'internal_tel',
        'personnel_code',
        'address',
        'receive_email',
        'receive_sms',
        'receive_messenger'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
