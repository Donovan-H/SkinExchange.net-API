<?php

namespace App\Models\PUBG;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	protected $table = "pubg_items";
	protected $primaryKey = "item_id_pk";
}
