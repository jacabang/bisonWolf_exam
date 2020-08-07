<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialRequests extends Model
{
    //
    
    protected $table = "special_requests";

    public function parent()
    {
        return $this->hasOne('App\Access', 'id', 'parent_id');
    }
}
