<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fbuser extends Model
{
    //

    public function activities()
    {
        return $this->hasMany('App\Activity');
    }
}
