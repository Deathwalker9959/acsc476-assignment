<?php

namespace App\Models;
use App\Models\Traits\TimeStamps;
use App\Models\Category;

class Team extends Model
{
    use TimeStamps;

    public function categories() {
        $this->hasManyThrough(Category::class, TeamCategory::class, "category_id", "team_id");
    }
}
