<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Linea;
use App\Models\Product;
use App\Models\ProductEvent;
use App\Models\ProductVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Validator;

class ProductController extends Controller
{
    public $dir_name = '/web/images/products/';
    public $dir_name_event = '/web/images/products/events/';

    /**
     ************************** list **********
     **/

    public function index(Request $request)
    {
        \App::setLocale($request->locale);
        $data = product::where("category_id", "=", 3)->paginate(env("PAGE_API"));
        foreach ($data as $k => $item) {
            $file = DB::table("products_image")->where("product_id", "=", $item->id)->value("url");
            $data[$k]["image"] = $file;
        }
        return response()->json($data);
    }

    public function searchById(Request $request, $id)
    {
        try {
            App::setLocale($request->locale);
            $product = Product::find(intval($id));
            $file = DB::table("products_image")->where("product_id", "=", $product->id)->value("url");
            $product["image"] = $file;
            return response()->json(["success" => true, "items" => $product]);
        } catch (\Throwable $th) {
            return response()->json(["success" => false]);
        }
    }

    /**
     ************************** detail **********
     **/
    public function detail(Request $request, $line, $product)
    {
        try {
            \App::setLocale($request->locale);
            $files = [];
            $product = Product::where("products.slug", "=", $product)
                ->where("lineas.slug", "=", $line)
                ->join("lineas", "lineas.id", "=", "products.linea_id")
                ->select("products.*")
                ->get();
            if (count($product)) {
                $files = DB::table("products_image")->where("product_id", "=", $product[0]["id"])->get();
            }
            $line = [];
            if (count($product) > 0) {
                $line = Linea::find($product[0]->linea_id)->translate($request->locale)->name;
            }

            return response()->json([
                "data" => count($product) > 0 ? $product[0] : [],
                "images" => $files,
                "rows" => count($product),
                "status" => "OK",
                "line" => $line,
            ]);
        } catch (Exception $e) {
            return response()->json(["message" => $e, "status" => 'Fail']);
        }
    }

    public function getProductByLinea(Request $request, $slug)
    {

        try {
            \App::setLocale($request->locale);
            $linea = Linea::where("slug", "=", $slug)->get();
            $id = count($linea) > 0 ? $linea[0]->id : 0;
            $data = Product::where("linea_id", "=", $id)->paginate(env("PAGE_API"));
            foreach ($data as $k => $v) {
                $file = DB::table("products_image")->where("product_id", "=", $v["id"])->get();
                $data[$k]["image"] = $file;
            }

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(["error" => $e]);
        }
    }




    public function ProductByLine(Request $request, $line)
    {
        try {
            $data = DB::table("product_translations")
                ->select(
                    "product_translations.name",
                    "product_translations.description",
                    DB::raw('(SELECT url FROM products_image WHERE product_id = products.id LIMIT 1) as image_url')
                )
                ->join("products", "product_translations.product_id", "=", "products.id")
                ->where("product_translations.locale", $request->locale)
                ->where("products.line_id", $line)
                ->get();


            $line_ = DB::table("linea_translations")
                ->select(
                    "lineas.id",
                    "linea_translations.name",
                    "linea_translations.description",
                    "lineas.category_id",
                    DB::raw('(SELECT url FROM lineas_image WHERE linea_id = lineas.id LIMIT 1) as image_url')
                )
                ->join("lineas", "linea_translations.linea_id", "=", "lineas.id")
                ->where("lineas.id", $line)
                ->where("linea_translations.locale", $request->locale)
                ->first();

            return response()->json(["products" => $data, "line" => $line_]);
        } catch (Exception $e) {
            return response()->json(["error" => $e]);
        }
    }

    public function ProductBySubCategory(Request $request, $id)
    {
        try {
            $data = DB::table("product_translations")
                ->select(
                    "product_translations.name",
                    "product_translations.description",
                    DB::raw('(SELECT url FROM products_image WHERE product_id = products.id LIMIT 1) as image_url')
                )
                ->join("products", "product_translations.product_id", "=", "products.id")
                ->where("product_translations.locale", $request->locale)
                ->where("products.subcategory_id", $id)
                ->get();


            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(["error" => $e]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tranlations' => 'required',
            'linea_id' => 'required',
        ]);

        if ($validator->passes()) {

            try {
                $req = $request->only('linea_id', 'tranlations');
                $data = [];
                $data["linea_id"] = $req["linea_id"];
                foreach ($req["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                \Log::debug($data);
                $linea = Product::create($data);
                return response()->json(["data" => $data, "msg" => "OK"]);
            } catch (\Exception $e) {
                \Log::debug($e);
                return response()->json(['error' => $e]);
            }
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }

    /**
     ************************** store multi files **********
     **/
    public function storeFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'photo' => 'required',
            'photo.*' => 'mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        $path = $request->getSchemeAndHttpHost() . $this->dir_name;

        try {
            if ($validator->passes()) {
                if ($request->hasfile('photo')) {
                    foreach ($request->file('photo') as $p) {
                        $nameOrigin = $p->getClientOriginalName();
                        $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();

                        $url = $path . $name;
                        $p->move(public_path() . $this->dir_name, $name);

                        DB::table("products_image")
                            ->insert(["name" => $name, "url" => $url, "product_id" => $request["product_id"]]);
                        \Log::debug($name);
                    }
                    return response()->json(['data' => $path, "msg" => "OK"]);
                }
            }

            return response()->json(['error' => $validator->errors()->all()]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    // *************************EVENT****************************************
    //

    public function getEventByProductLine(Request $request, $line, $product)
    {
        try {
            \App::setLocale($request->locale);
            $events = ProductEvent::where("products.slug", "=", $product)
                ->where("lineas.slug", "=", $line)
                ->join("products", "products.id", "=", "product_events.product_id")
                ->join("lineas", "lineas.id", "=", "products.linea_id")
                ->select("product_events.*")

                ->paginate(env("PAGE_API_LINE_EVENT"));
            return response()->json($events, 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e, "status" => "Fail"], 500);
        }
    }

    public function getEventByProductLineDetail(Request $request, $line, $prod, $event)
    {
        try {
            \App::setLocale($request->locale);
            $data = ProductEvent::where("product_events.slug", "=", $event)
                ->where("lineas.slug", "=", $line)
                ->where("products.slug", "=", $prod)
                ->join("products", "products.id", "=", "product_events.product_id")
                ->join("lineas", "lineas.id", "=", "products.linea_id")
                ->select("product_events.*", "lineas.id as linea_id")
                ->get();
            $line = [];
            $product = [];
            if (!$data->isEmpty()) {
                $line = Linea::find($data[0]->linea_id)->translate($request->locale)->name;
                $product = Product::find($data[0]->product_id)->translate($request->locale)->name;
            }

            return response()->json(
                [
                    'data' => count($data) > 0 ? $data[0] : [],
                    "status" => "OK",
                    "rows" => count($data),
                    "line" => $line,
                    "product" => $product,
                ],

                200
            );
        } catch (Exception $e) {
            return response()->json(['message' => $e, "status" => "Fail"], 500);
        }
    }

    public function storeEvent(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tranlations' => 'required',
            'product_id' => 'required',
            'date_event' => 'required',
            'photo' => 'mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        if ($validator->passes()) {
            try {
                $req = $request->only('product_id', 'tranlations', 'date_event');
                $data = [];
                $data["product_id"] = $req["product_id"];
                $data["date_event"] = $req["date_event"];
                foreach (json_decode($req["tranlations"], true) as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $linea = ProductEvent::create($data);
                if ($request->hasfile('photo')) {
                    $path = $request->getSchemeAndHttpHost() . $this->dir_name_event;
                    $p = $request->file('photo');
                    $nameOrigin = $p->getClientOriginalName();
                    $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();

                    $url = $path . $name;
                    $p->move(public_path() . $this->dir_name_event, $name);

                    $lineaEvent = ProductEvent::find($linea->id);
                    $lineaEvent->url = $url;
                    $lineaEvent->url_name = $name;
                    $lineaEvent->save();
                }

                return response()->json(["data" => $linea, "status" => "OK"]);
            } catch (\Exception $e) {
                \Log::debug($e);
                return response()->json(['message' => $e]);
            }
        }
        return response()->json(['message' => $validator->errors()->all()]);
    }

    //
    //***********************************VIDEOS*********************
    //

    public function getVideoByProductLine(Request $request, $line, $product)
    {
        try {
            \App::setLocale($request->locale);
            $videos = ProductVideo::where("products.slug", "=", $product)
                ->where("lineas.slug", "=", $line)
                ->join("products", "products.id", "=", "product_videos.product_id")
                ->join("lineas", "lineas.id", "=", "products.linea_id")
                ->select("product_videos.*")
                ->get();
            return response()->json(['data' => $videos, "status" => "OK", "rows" => count($videos)], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e, "status" => "Fail"], 500);
        }
    }
    public function storeVideo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tranlations' => 'required',
            'product_id' => 'required',
            'link' => 'required',

        ]);

        if ($validator->passes()) {
            try {
                $req = $request->only('product_id', 'tranlations', 'link');
                $data = [];
                $data["product_id"] = $req["product_id"];
                $data["link"] = explode("/", $req["link"])[3];

                foreach ($req["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }

                $linea = ProductVideo::create($data);

                return response()->json(["data" => $linea, "status" => "OK"]);
            } catch (\Exception $e) {
                \Log::debug($e);
                return response()->json(['message' => $e, "status" => "Fail"], 500);
            }
        }
        return response()->json(['message' => $validator->errors()->all(), "status" => 'Fail']);
    }
}
