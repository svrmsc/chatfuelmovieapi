<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    //

    public function activity_type()
    {
        return $this->hasOne('App\Activity_type');
    }
}
