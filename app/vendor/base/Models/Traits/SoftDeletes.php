<?php

namespace App\Models\Traits;

trait SoftDeletes
{
    protected $deleted_at;

    public function delete(int $id)
    {
        $this->attributes->deleted_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function restore()
    {
        $this->attributes->deleted_at = null;
        return $this->save();
    }
}
