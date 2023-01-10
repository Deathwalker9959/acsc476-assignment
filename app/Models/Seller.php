<?php

namespace App\Models;

use App\Models\Traits\Timestamps;

class Seller extends Model
{
    use Timestamps;

    public function teams()
    {
        return $this->hasManyThrough(Team::class, TeamUser::class, 'id', 'team_id', 'seller_id');
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
