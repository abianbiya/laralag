<?php

namespace Abianbiya\Laralag\Traits;

use Illuminate\Support\Facades\Auth;

trait HasAuditTrail
{
    public static function bootHasAuditTrail()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::check() ? Auth::id() : 'Guest';
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::check() ? Auth::id() : 'Guest';
        });

        static::deleting(function ($model) {
            $model->deleted_by = Auth::check() ? Auth::id() : 'Guest';
            $model->save();
        });

        static::restoring(function ($model) {
            $model->deleted_by = null;
            $model->save();
        });
    }
}
