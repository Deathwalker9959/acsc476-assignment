<?php

namespace App\Models;

use App\Models\Traits\Timestamps;

class Seller extends Model
{
    use Timestamps;

    public static $table = "sellers";

    public function teams()
    {
        return $this->hasMany(Team::class,'owner_id','id');
    }

    public function teamIds()
    {
        return array_map(
            function ($team) {
                return $team->id;
            },
            $this->teams()
        );
    }
}
