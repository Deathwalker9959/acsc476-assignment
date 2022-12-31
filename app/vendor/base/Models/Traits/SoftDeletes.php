<?php

namespace App\Models\Traits;

trait SoftDeletes
{
    protected $deleted_at;

    public static function delete(int $id)
    {
        static::$deleted_at = date('Y-m-d H:i:s');
    }

    public static function restore()
    {
        $this->deleted_at = null;
        $this->save();
    }
}