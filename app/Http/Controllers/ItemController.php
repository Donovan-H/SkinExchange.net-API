<?php

namespace App\Http\Controllers;

use App\Items as Items;

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
