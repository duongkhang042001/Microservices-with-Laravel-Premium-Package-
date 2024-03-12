<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Schema;

abstract class Model extends EloquentModel
{
    protected $guarded = [];
}
