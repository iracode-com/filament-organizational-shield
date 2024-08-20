<?php

namespace IracodeCom\FilamentOrganizationalShield\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'status'];
}
