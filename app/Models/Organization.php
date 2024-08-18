<?php

namespace App\Models;

use App\Observers\OrganizationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(OrganizationObserver::class)]
class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'logo', 'icon', 'tel', 'fax', 'industry', 'personnel_count', 'address', 'national_id', 'economy_code'];

    // Relationships

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, table: 'organization_user');
    }

    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }
}
