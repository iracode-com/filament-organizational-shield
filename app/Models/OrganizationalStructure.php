<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationalStructure extends Model
{
    protected $fillable = ['organization_id', 'parent_id', 'order', 'name'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
