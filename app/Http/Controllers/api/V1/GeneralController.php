<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Linea;
use App\Models\LineaEvent;
use App\Models\LineaVideo;
use App\Models\Product;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function getMenuLinea(Request $request)
    {

        try {
            $menu = Linea::select("categories.name as cate", "lineas.id as linea_id", "linea_translations.name as linea", "lineas.slug")
                ->join("categories", "categories.id", "=", "lineas.category_id")
                ->join("linea_translations", "lineas.id", "=", "linea_translations.linea_id")
                ->where("linea_translations.locale", "=", $request->locale)
                ->get();

            $data  = [];
            $check = null;
            foreach ($menu as $key => $v) {

                $indx = $this->searchForId($v["cate"], $data);

                if ($indx === null) {
                    $data[] = [
                        "name" => $v["cate"], "lineas" => [["name" => $v["linea"], "id" => $v["linea_id"],
                            "slug"                                     => $v["slug"]]],
                    ];

                } else {
                    array_push($data[$indx]["lineas"], ["name" => $v["linea"], "id" => $v["linea_id"], "slug" => $v["slug"]]);
                }

            }
            return response()->json(["data" => $data, "msg" => 'OK']);
        } catch (\Exception $e) {
            return response()->json(["data" => $e, "msg" => 'Fail']);
        }

    }

    public function searchForId($id, $array)
    {

        foreach ($array as $key => $val) {
            if ($val['name'] == $id) {
                return $key;
            }
        }
        return null;
    }

    public static function slugName($name)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $name);
        return strtolower($slug);
    }

    public function getMenuTree(Request $request, $id)
    {
        \App::setLocale($request->locale);
        \Log::debug($id);
        try {
            // $products = Product::where("linea_id","=", $id)->get();
            $products = Product::select("products.slug", "product_translations.name as product","product_translations.locale")
                ->where("products.linea_id","=", $id)
                ->where("product_translations.locale","=", $request->locale)
                ->leftjoin("product_translations", "products.id", "=", "product_translations.product_id")
                ->get();

            $events = LineaEvent::select("linea_events.slug", "linea_event_translations.title as event")
                ->where("linea_events.linea_id","=", $id)
                ->where("linea_event_translations.locale","=", $request->locale)
                ->leftjoin("linea_event_translations", "linea_events.id", "=", "linea_event_translations.linea_event_id")
                ->get();


            $videos = LineaVideo::where("linea_id", "=", $id)->get();

            return response()->json(["products" => $products,
                "events"                            => $events,
                "videos"                            => $videos,
                "status"                            => 'OK'], 200);
        } catch (Exception $e) {
            return response()->json(["message" => $e], 500);
        }

    }
}
