<?php

namespace App\Models;

use App\Models\Traits\SoftDeletes;
use App\Models\Traits\Timestamps;

class Product extends Model
{
    use Timestamps, SoftDeletes;
}
