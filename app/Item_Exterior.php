<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item_Exterior extends Model
{
	protected $table = "item_exterior";
	protected $fillable = ['exterior'];
	public $timestamps = false;

	public function items()
    {
        return $this->belongsToMany('App\Item', 'exterior_id_fk');
    }
}
