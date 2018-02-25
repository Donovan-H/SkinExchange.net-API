<?php

namespace App\Http\Controllers;

use App\Models\CSGO\Item as CSGO_Items;
use App\Models\CSGO\Item_Category as CSGO_Item_Category;
use App\Models\CSGO\Item_Collection as CSGO_Item_Collection;
use App\Models\CSGO\Item_Exterior as CSGO_Item_Exterior;
use App\Models\CSGO\Item_Quality as CSGO_Item_Quality;
use App\Models\CSGO\Item_Type as CSGO_Item_Type;
use App\Models\CSGO\Item_Weapon as CSGO_Item_Weapon;

use App\Models\PUBG\Item as PUBG_Items;

use App\Models\Proxy as Proxy;

const CSGO = 730;
const PUBG = 578080;

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

    public function proxyRequest($url)
    {
        $proxy = Proxy::where('isActive', '1')->orderBy('updated_at', 'ASC')->first();
        
        $proxy->isActive = '0';
        $proxy->save();

        $auth = base64_encode("{$proxy->username}:{$proxy->password}");

        $proxyConnect = array(
            'http' => array(
                'proxy' => "tcp://{$proxy->ip_address}:{$proxy->port}",
                'request_fulluri' => true,
                'header' => array("Proxy-Authorization: Basic $auth"),
            ),
        );

        $contentStream = stream_context_create($proxyConnect);
        $content = file_get_contents($url, False, $contentStream);

        $proxy->isActive = '1';
        $proxy->save();

        return $content;
    }

    public function getInventory($appid, $steamid)
    {
        $response = [
            'inventory' => []
        ];

        if($appid != CSGO && $appid != PUBG) {
            return array("success" => "false");
        }

        $url = sprintf('http://steamcommunity.com/inventory/%s/%d/2?l=english&count=5000', $steamid, $appid);

        $content = $this->proxyRequest($url);
        //$content = file_get_contents($url);

        $json = json_decode($content, true);
        
        if ($json["success"] != true) {
            return array("success" => "false");
        }

        if (empty($json['descriptions'])) {
            return array("success" => "false");
        }

        //print_r(array_count_values($json['rgInventory']));
        $start_time = microtime(TRUE);
        foreach($json['descriptions'] as $index => $v) {
            if($appid == CSGO) {
                $item = new CSGO_Items;
            } elseif($appid == PUBG) {
                $item = new PUBG_Items;
            } else {
                return array("success" => "false");
            }
            //"id":"7554053113","classid":"310776586","instanceid":"302028390","amount":"1","pos":39
            //Gets asset ID from inventory then heads straight to pos in description array
            if ($v['appid'] == CSGO && $v['marketable'] == 1 && $v['tradable'] == 1) {
                $item->name = $v['name'];
                $item->market_name = $v['market_hash_name'];
                $item->image = $v['icon_url'];
                $item->class_id_fpk = $v['classid'];
                $exterior = "";
                $instanceid = $v['instanceid'];

                foreach ($v['tags'] as $tag) {
                    
                    switch ($tag['localized_category_name']) {
                        case 'Type':
                            # Weapon Category e.g Shotgun
                            $type = CSGO_Item_Type::firstOrCreate(['type' => $tag['localized_tag_name']]);
                            if ($type->id) $type->type_id_pk = $type->id;
                            $item->type_id_fk = $type->type_id_pk;
                            break;
                        case 'Weapon':
                            # Base Weapon e.g Nova
                            $weapon = CSGO_Item_Weapon::firstOrCreate(['weapon' => $tag['localized_tag_name']]);
                            if ($weapon->id) $weapon->weapon_id_pk = $weapon->id;                            
                            $item->weapon_id_fk = $weapon->weapon_id_pk;
                            break;
                        case 'Collection':
                            # Weapon Collection e.g The Italy Collection
                            $collection = CSGO_Item_Collection::firstOrCreate(['collection' => $tag['localized_tag_name']]);
                            if ($collection->id) $collection->collection_id_pk = $collection->id;
                            $item->collection_id_fk = $collection->collection_id_pk;
                            break;
                        
                        case 'Category':
                            # Weapon Category e.g Statrak
                            $category = CSGO_Item_Category::firstOrCreate(['category' => $tag['localized_tag_name']]);
                            if ($category->id) $category->category_id_pk = $category->id;
                            $item->category_id_fk = $category->category_id_pk;
                            $category = $tag['localized_tag_name'];
                            break;

                        case 'Quality':
                            # Rarity e.g Consumer Grade + Color
                            $quality = CSGO_Item_Quality::firstOrCreate(['quality' => $tag['localized_tag_name']], ['color' => $tag['color']]);
                            if ($quality->id) $quality->quality_id_pk = $quality->id;
                            $item->quality_id_fk = $quality->quality_id_pk;
                            $quality_color = $tag['color'];
                            break;

                        case 'Exterior':
                            # Weapon Wear
                            $exterior = CSGO_Item_Exterior::firstOrCreate(['exterior' => $tag['localized_tag_name']]);
                            if ($exterior->id) $exterior->exterior_id_pk = $exterior->id;
                            $item->exterior_id_fk = $exterior->exterior_id_pk;
                            $exterior = $tag['localized_tag_name'];
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
                if (!CSGO_Items::where('market_name', '=', $item->market_name)->exists()) {
                    $item->save();
                }
                $response['inventory'][] = [
                    "appid"           =>  $json["assets"][$index]['appid'],
                    "assetid"           =>  $json["assets"][$index]['assetid'],
                    "instanceid"        =>  $v["instanceid"],
                    "classid"           =>  $item->class_id_fpk,
                    "name"              =>  $item->name,
                    "market_name"       =>  $item->market_name,
                    "image"             =>  $item->image,
                    "csgo_category"          =>  $category,
                    "csgo_exterior"          =>  $exterior,
                    "csgo_quality_color"     =>  $quality_color
                ];
            }
            if ($v['appid'] == PUBG && $v['marketable'] == 1) {
                $item->class_id_fpk = $v['classid'];
                $item->name = $v['name'];
                $item->market_name = $v['market_hash_name'];
                $item->image = $v['icon_url'];
                $item->image_large = $v['icon_url_large'];
                $item->name_color = $v['name_color'];
                $item->background_color = $v['background_color'];
                $item->type = $v['type'];

                if (!PUBG_Items::where('class_id_fpk', '=', $item->class_id_fpk)->exists()) {
                    $item->save();
                }
                $response['inventory'][] = [
                    "assetid"           =>  $inv["id"],
                    "instanceid"        =>  $inv["instanceid"],
                    "classid"           =>  $item->class_id_fpk,
                    "amount"            =>  $inv["amount"],
                    "name"              =>  $item->name,
                    "market_name"       =>  $item->market_name,
                    "image"             =>  $item->image
                ];
            }
        }
       
        $end_time = microtime(TRUE);
        $total_time = ($end_time - $start_time);
        $response["time_elapsed"][] = $total_time;
        $response["proxy"][] = '';
        return $response;
    }

    public function getPrice($market_name)
    {
        # code...
    }


    public function getItem($classid)
    {
        return CSGO_Items::where('class_id_fpk', $classid)->get();
    }

    public function getCollections()
    {
        return CSGO_Item_Collection::select('collection')->get();
    }

    public function getCategories()
    {
        return CSGO_Item_Category::select('category')->get();
    }
    public function getWeapons()
    {
        return CSGO_Item_Weapon::select('weapon')->get();
    }
    public function getTypes()
    {
        return CSGO_Item_Type::select('type')->get();
    }
}
