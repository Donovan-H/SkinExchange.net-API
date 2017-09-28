<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
	protected $table = "proxies";
	protected $fillable = ['ip_address', 'port', 'isActive'];
	public $timestamps = true;
}
