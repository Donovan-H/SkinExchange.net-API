<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item_Weapon extends Model
{
	protected $table = "item_weapon";
	protected $fillable = ['weapon'];
	public $timestamps = false;

	public function items()
    {
        return $this->belongsToMany('App\Item', 'weapon_id_fk');
    }
}
