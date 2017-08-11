<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $table = 'item_db';

    public $timestamps = false;
    
    public function dbitem()
    {
    	return $this->belongsTo('App\Trade_Items');
    }
}
