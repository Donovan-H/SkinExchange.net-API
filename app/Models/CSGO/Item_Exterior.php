<?php

namespace App\Models\CSGO;

use Illuminate\Database\Eloquent\Model;

class Item_Exterior extends Model
{
	protected $table = "csgo_item_exterior";
	protected $fillable = ['exterior'];
	public $timestamps = false;

	public function items()
    {
        return $this->belongsToMany('App\Item', 'exterior_id_fk');
    }
}
