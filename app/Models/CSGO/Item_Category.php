<?php

namespace App\Models\CSGO;

use Illuminate\Database\Eloquent\Model;

class Item_Category extends Model
{
	protected $table = "csgo_item_category";
	protected $fillable = ['category'];
	public $timestamps = false;

	public function items()
    {
        return $this->belongsToMany('App\Item', 'category_id_fk');
    }
}
