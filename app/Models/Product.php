<?php

namespace App\Models;

use App\Models\Traits\SoftDeletes;
use App\Models\Traits\TimeStamps;

class Product extends Model
{
    use TimeStamps, SoftDeletes;
}
