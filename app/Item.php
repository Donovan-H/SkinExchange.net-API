<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	protected $table = "items";
	protected $primaryKey = "item_id_pk";
	protected $fillable = [
        'item_id_pk', 'type_id_fk', 'weapon_id_fk', 'collection_id_fk', 'category_id_fk', 'name', 'market_name', 'image'
    ];

	public function category() {
        return $this->hasOne('App\Item_Category');
    }

    public function collection() {
        return $this->hasOne('App\Item_Collection');
    }

    public function exterior() {
        return $this->hasOne('App\Item_Exterior');
    }

    public function quality() {
        return $this->hasOne('App\Item_Quality');
    }

    public function type() {
        return $this->hasOne('App\Item_Type');
    }

    public function weapon() {
        return $this->hasOne('App\Item_Weapon');
    }
}
