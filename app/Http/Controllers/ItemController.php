<?php

namespace App\Http\Controllers;

use App\Models\CSGO\Item as Items;

use App\Models\CSGO\Item_Category as Item_Category;
use App\Models\CSGO\Item_Collection as Item_Collection;
use App\Models\CSGO\Item_Exterior as Item_Exterior;
use App\Models\CSGO\Item_Quality as Item_Quality;
use App\Models\CSGO\Item_Type as Item_Type;
use App\Models\CSGO\Item_Weapon as Item_Weapon;

use App\Models\Proxy as Proxy;

class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getInventory($steamid)
    {
        
            $response = [
                'inventory' => []
            ];
            $statusCode = 200;

            $proxy = Proxy::where('isActive', '1')->orderBy('updated_at', 'ASC')->first();

            $proxy->isActive = '0';
            $proxy->save();

            $auth = base64_encode("{$proxy->username}:{$proxy->password}");

            $proxy->isActive = '1';
            $proxy->save();

            $proxyConnect = array(
                'http' => array(
                    'proxy' => "tcp://{$proxy->ip_address}:{$proxy->port}",
                    'request_fulluri' => true,
                    'header' => array("Proxy-Authorization: Basic $auth"),
                ),
            );

            $contentStream = stream_context_create($proxyConnect);

            $url = sprintf('http://steamcommunity.com/profiles/%s/inventory/json/730/2', $steamid);
            $content = file_get_contents($url, False, $contentStream);
            //$content = file_get_contents($url);

            $json = json_decode($content, true);

            if ($json["success"] != "true") {
                return array("success" => "false");
            }

            if (empty($json['rgDescriptions'])) {
                return array("success" => "false");
            }

            //print_r(array_count_values($json['rgInventory']));
            $start_time = microtime(TRUE);
            foreach($json['rgDescriptions'] as $v) {
                //print_r($v) ;
                if ($v['appid'] == 730 && $v['marketable'] == 1) {
                    $item = new Items;

                    $item->name = $v['name'];
                    $item->market_name = $v['market_hash_name'];
                    $item->image = $v['icon_url'];
                    $item->class_id_fpk = $v['classid'];

                    foreach ($v['tags'] as $tag) {
                        
                        switch ($tag['category_name']) {
                            case 'Type':
                                # Weapon Category e.g Shotgun
                                $type = Item_Type::firstOrCreate(['type' => $tag['name']]);
                                if ($type->id) $type->type_id_pk = $type->id;
                                $item->type_id_fk = $type->type_id_pk;
                                break;
                            case 'Weapon':
                                # Base Weapon e.g Nova
                                $weapon = Item_Weapon::firstOrCreate(['weapon' => $tag['name']]);
                                if ($weapon->id) $weapon->weapon_id_pk = $weapon->id;                            
                                $item->weapon_id_fk = $weapon->weapon_id_pk;
                                break;
                            case 'Collection':
                                # Weapon Collection e.g The Italy Collection
                                $collection = Item_Collection::firstOrCreate(['collection' => $tag['name']]);
                                if ($collection->id) $collection->collection_id_pk = $collection->id;
                                $item->collection_id_fk = $collection->collection_id_pk;
                                break;
                            
                            case 'Category':
                                # Weapon Category e.g Statrak
                                $category = Item_Category::firstOrCreate(['category' => $tag['name']]);
                                if ($category->id) $category->category_id_pk = $category->id;
                                $item->category_id_fk = $category->category_id_pk;
                                break;

                            case 'Quality':
                                # Rarity e.g Consumer Grade + Color
                                $quality = Item_Quality::firstOrCreate(['quality' => $tag['name']], ['color' => $tag['color']]);
                                if ($quality->id) $quality->quality_id_pk = $quality->id;
                                $item->quality_id_fk = $quality->quality_id_pk;
                                break;

                            case 'Exterior':
                                # Weapon Wear
                                $exterior = Item_Exterior::firstOrCreate(['exterior' => $tag['name']]);
                                if ($exterior->id) $exterior->exterior_id_pk = $exterior->id;
                                $item->exterior_id_fk = $exterior->exterior_id_pk;
                                break;
                                
                            default:
                                # code...
                                break;
                        }

                        if (!$item->collection_id_fk) {
                            $item->collection_id_fk = 1;
                        }

                        if (!$item->weapon_id_fk) {
                            $item->weapon_id_fk = 1;
                        }

                        if (!$item->exterior_id_fk) {
                            $item->exterior_id_fk = 1;
                        }

                    }
                    $end_time = microtime(TRUE);
                    if (Items::where('market_name', '=', $item->market_name)->exists()) {
                        continue;
                    } else {
                        $item->save();
                        $response['inventory'][] = [
                            "id"                =>  $item->id,
                            "name"              =>  $item->name,
                            "market_name"       =>  $item->market_name,
                            "image"             =>  $item->image
                        ];
                    }
                }
            }
            $total_time = ($end_time - $start_time);
            $response["time_elapsed"][] = $total_time;
            $response["proxy"][] = '';
            return $response;
    }


    public function getItem($classid)
    {
        return Items::where('class_id_fpk', $classid)->get();
    }

    public function getCollections()
    {
        return Item_Collection::select('collection')->get();
    }

    public function getCategories()
    {
        return Item_Category::select('category')->get();
    }
    public function getWeapons()
    {
        return Item_Weapon::select('weapon')->get();
    }
    public function getTypes()
    {
        return Item_Type::select('type')->get();
    }
}
