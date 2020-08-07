<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    //
    protected $table = "requests";

    public function exclution_time()
    {
        return $this->hasOne('App\ExclusionOfTime', 'requests_id', 'id');
    }

    public function accomodation()
    {
        return $this->hasOne('App\Accommodations', 'requests_id', 'id');
    }

    public function special_request()
    {
        return $this->hasOne('App\SpecialRequests', 'requests_id', 'id');
    }
}
