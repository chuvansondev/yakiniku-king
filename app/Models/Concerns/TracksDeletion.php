<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;

trait TracksDeletion
{
    public static function bootTracksDeletion(): void
    {
        static::deleting(function (Model $model): void {
            if (! $model->isForceDeleting()) {
                $model->setAttribute('delete_by', auth()->id());
                $model->saveQuietly();
            }
        });

        static::restoring(function (Model $model): void {
            $model->setAttribute('delete_by', null);
        });
    }
}
