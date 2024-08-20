<?php

namespace IracodeCom\FilamentOrganizationalShield\Models;

use IracodeCom\FilamentOrganizationalShield\Observers\StructureObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SolutionForest\FilamentTree\Concern\ModelTree;

#[ObservedBy(StructureObserver::class)]
class Structure extends Model
{
    use SoftDeletes, ModelTree;

    protected $table    = 'organizational_structures';

    protected $fillable = ['parent_id', 'title', 'order'];

    // Scopes
    public function scopeParent(Builder $query): Builder
    {
        return $query->whereNull('parent_id')->orWhere('parent_id', -1);
    }

    // Relationships

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->with('children');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
