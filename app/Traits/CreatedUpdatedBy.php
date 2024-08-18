<?php

namespace App\Traits;

trait CreatedUpdatedBy
{
    public static function bootCreatedUpdatedBy(): void
    {
        static::creating(function ($model) {
            if ($model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'created_by') && ! $model->isDirty('created_by')) {
                $model->created_by = auth()->user()->id;
            }
            if ($model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'updated_by') && ! $model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });

        static::updating(function ($model) {
            if ($model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'updated_by') && ! $model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });
    }
}