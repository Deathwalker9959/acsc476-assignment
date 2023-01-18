<?php

namespace App\Models;

use App\Models\Traits\Timestamps;
use App\Models\Category;

class Team extends Model
{
    use Timestamps;

    public function products()
    {
        $this->hasMany(Product::class, 'team_id', 'id');
    }

    public function categories()
    {
        $this->hasMany(Category::class, 'team_id', 'id');
    }
}
