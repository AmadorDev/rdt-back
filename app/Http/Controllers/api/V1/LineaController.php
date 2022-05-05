<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Linea;
use App\Models\LineaEvent;
use App\Models\LineaVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class LineaController extends Controller
{
    public $dir_name_event = '/web/images/lineas/event/';

    /**
     ************************** list **********
     **/

    public function index(Request $request)
    {
        $data = Linea::paginate(env("PAGE_API"));
        foreach ($data as $item) {
            $files = DB::table("lineas_image")->where("linea_id", "=", $item->id)->get();
            $item["files"] = $files;
        }
        return response()->json($data);
    }

    public function Featureds(Request $request)
    {
        try {
            \App::setLocale($request->locale);
            $data = Linea::where("featured", "=", 1)->get();

            $img_l = $request->getSchemeAndHttpHost() . '/web/bg-image.png';
            foreach ($data as $k => $v) {
                $image = DB::table("lineas_image")->where("linea_id", "=", $data[$k]->id)
                    ->where('cover', "=", 1)->value('url');
                $data[$k]["image"] = $image ? $image : $img_l;
            }

            return response()->json(["data" => $data, "rows" => count($data), "status" => "OK"], 200);

        } catch (Exception $e) {
            return response()->json(["message" => $e, "status" => "Fail"], 500);
        }
    }

/**
 ************************** detail **********
 **/
    public function detail(Request $request, $slug)
    {
        \App::setLocale($request->locale);
        $files = [];
        $lineas = Linea::where("slug", "=", $slug)->get();

        if (count($lineas)) {
            $files = DB::table("lineas_image")->where("linea_id", "=", $lineas[0]["id"])
                ->where('cover', "=", 0)->get();
        }

        return response()->json(["data" => count($lineas) ? $lineas[0] : [], "files" => $files, "rows" => count($lineas)]);
    }

/**
 ************************** store **********
 **/
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tranlations' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->passes()) {
            try {
                $req = $request->only('category_id', 'tranlations');
                $data = [];
                $data["category_id"] = $req["category_id"];
                foreach ($req["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $linea = Linea::create($data);
                return response()->json(["data" => $linea, "msg" => "OK"]);
            } catch (\Exception$e) {
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
            'linea_id' => 'required',
            'photo' => 'required',
            'photo.*' => 'mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        $path = $request->getSchemeAndHttpHost() . '/web/images/lineas/';

        try {
            if ($validator->passes()) {
                if ($request->hasfile('photo')) {
                    foreach ($request->file('photo') as $p) {
                        $nameOrigin = $p->getClientOriginalName();
                        $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();

                        $url = $path . $name;
                        $p->move(public_path() . '/web/images/lineas/', $name);

                        DB::table("lineas_image")
                            ->insert(["name" => $name, "url" => $url, "linea_id" => $request["linea_id"]]);
                    }

                }
                return response()->json(['data' => $path, "msg" => "OK"]);
            }

            return response()->json(['error' => $validator->errors()->all()]);
        } catch (\Exception$e) {
            return response()->json(['error' => $e]);
        }
    }

/**
 ************************** events to linea **********
 **/

    public function getEventByLinea(Request $request, $id)
    {
        try {
            \App::setLocale($request->locale);
            $events = LineaEvent::where("linea_id", "=", $id)->paginate(env("PAGE_API_LINE_EVENT"));
            foreach ($events as $key => $value) {
                $imgs = DB::table("testing_images")->where("linea_event_id","=",$value->id)->get();
                $events[$key]["images"]= $imgs;
               
            }
            return response()->json(['data' => $events, "status" => "OK", "rows" => count($events)], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e, "status" => "Fail"], 500);
        }
    }

    public function getEventDetail(Request $request, $line, $event)
    {
        try {
            \App::setLocale($request->locale);
            $data = LineaEvent::where("linea_events.slug", "=", $event)
                ->where("lineas.slug", "=", $line)
                ->join("lineas", "lineas.id", "=", "linea_events.linea_id")
                ->select("linea_events.*", )
                ->get();
            $line = [];
            if (count($data) > 0) {
                $line = DB::table("linea_translations")
                    ->where("linea_id", "=", $data[0]->linea_id)
                    ->where("locale", "=", $request->locale)
                    ->select("name")->get();
            }

            return response()->json(
                ['data' => count($data) > 0 ? $data[0] : [],
                    "status" => "OK",
                    "rows" => count($data),
                    "line" => count($line) > 0 ? $line[0] : [],
                ],

                200);
        } catch (Exception $e) {
            return response()->json(['message' => $e, "status" => "Fail"], 500);
        }
    }

    public function storeEvent(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tranlations' => 'required',
            'linea_id' => 'required',
            'date_event' => 'required',
            'photo' => 'mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        if ($validator->passes()) {
            try {
                $req = $request->only('linea_id', 'tranlations', 'date_event');
                $data = [];
                $data["linea_id"] = $req["linea_id"];
                $data["date_event"] = $req["date_event"];
                foreach (json_decode($req["tranlations"], true) as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $linea = LineaEvent::create($data);
                if ($request->hasfile('photo')) {
                    $path = $request->getSchemeAndHttpHost() . $this->dir_name_event;
                    $p = $request->file('photo');
                    $nameOrigin = $p->getClientOriginalName();
                    $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();

                    $url = $path . $name;
                    $p->move(public_path() . $this->dir_name_event, $name);

                    $lineaEvent = LineaEvent::find($linea->id);
                    $lineaEvent->url = $url;
                    $lineaEvent->url_name = $name;
                    $lineaEvent->save();
                }

                return response()->json(["data" => $linea, "msg" => "OK"]);
            } catch (\Exception$e) {
                \Log::debug($e);
                return response()->json(['error' => $e]);
            }
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }

/**
 ************************** videos to linea **********
 **/

    public function getVideoByLinea(Request $request, $id)
    {
        try {
            \App::setLocale($request->locale);
            $events = LineaVideo::where("linea_id", "=", $id)->get();
            return response()->json(['data' => $events, "status" => "OK", "rows" => count($events)], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e, "status" => "Fail"], 500);
        }
    }
    public function storeVideo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tranlations' => 'required',
            'linea_id' => 'required',
            'link' => 'required',

        ]);

        if ($validator->passes()) {
            try {
                $req = $request->only('linea_id', 'tranlations', 'link');
                $data = [];
                $data["linea_id"] = $req["linea_id"];
                $data["link"] = explode("/", $req["link"])[3];

                foreach ($req["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }

                $linea = LineaVideo::create($data);

                return response()->json(["data" => $linea, "msg" => "OK"]);
            } catch (\Exception$e) {
                \Log::debug($e);
                return response()->json(['error' => $e]);
            }
        }
        return response()->json(['message' => $validator->errors()->all(), "status" => 'Fail']);
    }

    public function resultTest(Request $request, Linea $line)
    {
        try {
            \App::setLocale($request->locale);
            $img_default = $request->getSchemeAndHttpHost() . '/web/bg-image.png';
            $imageLine =DB::table("lineas_image")
            ->where("cover","=",1)
            ->where('linea_id',"=",$line->id)->value('url');
            $line["image"]= $imageLine?:$img_default;
            return response()->json(['line' => $line]);
        } catch (\Throwable $th) {
            return response()->json(['error' => json_encode( $th->getMessage())]);
        }
        

    }

}
