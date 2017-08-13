<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item_Collection extends Model
{
	protected $table = "item_collection";
	protected $fillable = ['collection'];
	public $timestamps = false;

	public function items()
    {
        return $this->belongsToMany('App\Item', 'collection_id_fk');
    }
}
