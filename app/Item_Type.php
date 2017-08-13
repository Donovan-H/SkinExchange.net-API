<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item_Type extends Model
{
	protected $table = "item_type";
	protected $fillable = ['type'];
	public $timestamps = false;

	public function items()
    {
        return $this->belongsToMany('App\Item', 'type_id_fk');
    }
}
