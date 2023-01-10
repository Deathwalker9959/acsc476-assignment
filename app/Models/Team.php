<?php

namespace App\Models;
use App\Models\Traits\Timestamps;
use App\Models\Category;

class Team extends Model
{
    use Timestamps;

    public function categories() {
        $this->hasManyThrough(Category::class, TeamCategory::class, "category_id", "team_id");
    }
}
