<?php

namespace App\Http\Controllers;

use App\Item as Items2;
use App\Items2 as Items;

use App\Item_Category as Item_Category;
use App\Item_Collection as Item_Collection;
use App\Item_Exterior as Item_Exterior;
use App\Item_Quality as Item_Quality;
use App\Item_Type as Item_Type;
use App\Item_Weapon as Item_Weapon;

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

    public function create($steamid)
    {
        
            $response = [
                'inventory' => []
            ];
            $statusCode = 200;


            $url = sprintf('http://steamcommunity.com/profiles/%s/inventory/json/730/2', $steamid);
            $content = file_get_contents($url);

            $json = json_decode($content, true);

            if (empty($json['rgDescriptions'])) {
                //throw new Exception('Invalid SteamID.'); Need error thorwing
                return 0;
            }

            //print_r(array_count_values($json['rgInventory']));
            $start_time = microtime(TRUE);
            foreach($json['rgDescriptions'] as $v) {
                //print_r($v) ;
                if ($v['appid'] == 730 && $v['marketable'] == 1) {
                    $item = new Items2;

                    $item->name = $v['name'];
                    $item->market_name = $v['market_hash_name'];
                    $item->image = $v['icon_url'];

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
                    if (Items2::where('market_name', '=', $item->market_name)->exists()) {
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

                    /*$item_result = Items2::where('market_name', '=', $item->market_name)->first();
                    if (empty($item_result)) {
                        $item->save();
                        $item_result = Items2::where('market_name', '=', $item->market_name)->first();

                    } 

                    $item_result->quantity = 0;

                    foreach ($json['rgInventory'] as $instance) {
                        if ($instance['classid'] == $v['classid']) {
                            $item_result->quantity += 1;
                        }
                    }

                    $response['inventory'][] = [
                        "id"                =>  $item_result->id,
                        "name"              =>  $item_result->name,
                        "market_name"       =>  $item_result->market_name,
                        "type"              =>  $item_result->type,
                        "weapon"            =>  $item_result->weapon,
                        "collection"        =>  $item_result->collection,
                        "category"          =>  $item_result->category,
                        "exterior"          =>  $item_result->exterior,
                        "quality"           =>  $item_result->quality,
                        "quality_color"     =>  $item_result->quality_color,
                        "image"             =>  $item_result->image,
                        "quantity"          =>  $item_result->quantity,
                        ];*/
                }
            }
            $total_time = ($end_time - $start_time);
            $response["time_elapsed"][] = $total_time;
            return $response;
    }

    public function get($id)
    {
        return Items::findorfail($id);
    }

    public function getCategories()
    {
        $categories = Items::select('type')->distinct()->get();

        foreach ($categories as $category) {
            $output[] = $category["type"];
        }
        return array("categories"=>$output);
    }

    public function getCollections() 
    {
        $collections = Items::select('collection')->distinct()->get();

        foreach ($collections as $collection) {
            if ($collection["collection"] == "") {
                $collection["collection"] = "None";
            }
            $output[] = $collection["collection"];
        }
        return array("collections"=>$output);
    }

    public function getCollection($collection) 
    {
        $collection = urldecode($collection);//escape this shit
        return Items::select('collection')->where('collection','=', "$collection" )->first();
    }

    public function getCollectionItems($collection)
    {
        $collection = urldecode($collection);
        return Items::where('collection','LIKE', "%$collection%" )->get();
    }
}
