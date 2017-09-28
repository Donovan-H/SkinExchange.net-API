<?php

namespace App\Models\CSGO;

use Illuminate\Database\Eloquent\Model;

class Item_Quality extends Model
{
	protected $table = "csgo_item_quality";
	protected $fillable = ['quality', 'color'];
	
	public $timestamps = false;
	public function items()
    {
        return $this->belongsToMany('App\Item', 'quality_id_fk');
    }
}
