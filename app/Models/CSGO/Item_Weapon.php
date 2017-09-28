<?php

namespace App\Models\CSGO;

use Illuminate\Database\Eloquent\Model;

class Item_Weapon extends Model
{
	protected $table = "csgo_item_weapon";
	protected $fillable = ['weapon'];
	public $timestamps = false;

	public function items()
    {
        return $this->belongsToMany('App\Item', 'weapon_id_fk');
    }
}
