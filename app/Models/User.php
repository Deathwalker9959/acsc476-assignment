<?php

namespace App\Models;

use App\Models\Traits\Timestamps;
use App\Models\Wishlist;

class User extends Model
{
    use Timestamps;
    public static $table = 'users';
    protected $hidden = ['password', 'remember_token'];

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, "user_id", "id");
    }

    public function wishlist($teamId)
    {
        $filtered_array = array_filter($this->wishlists(), function ($wishlist) use ($teamId) {
            return $wishlist->team_id == $teamId;
        });

        return reset($filtered_array);
    }
}
