<?php

namespace App\Models;

use App\Models\Traits\TimeStamps;

class Seller extends Model
{
    use TimeStamps;

    public function teams()
    {
        return $this->hasManyThrough(Team::class, TeamUser::class, 'id', 'team_id', 'seller_id');
    }
}
