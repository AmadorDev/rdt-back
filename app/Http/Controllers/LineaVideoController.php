<?php

namespace App\Http\Controllers;

use App\Models\Linea;
use App\Models\LineaVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Validator;

class LineaVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ["search" => $request->search];
        return Inertia::render('Dashboard/Lines/Videos/Index', [
            'filters' => $filter,
            'videos' => LineaVideo::filter($filter)->orderBy('created_at', 'desc')->paginate(env('PAGINATE')),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Linea $line)
    {
      
        return Inertia::render('Dashboard/Lines/Videos/Create', [
            'line' => ["id"=>$line->id,"name"=>$line->name],

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLineaVideoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.title' => 'required',
            'tranlations.*.content' => 'required',
            'linea_id' => 'required',
            'link' => 'required|url',

        ]);
        \Log::debug($request);

        if ($validator->passes()) {
            try {
                $req = $request->only('linea_id', 'tranlations', 'link');
                $data = [];
                $data["linea_id"] = $req["linea_id"];
                $data["link"] = $this->ValidateUrl($req["link"]);
                foreach ($req["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $linea = LineaVideo::create($data);
                return Redirect::route('video');
            } catch (\Throwable $e) {

                return Redirect::back()
                    ->withErrors($e->getMessage())
                    ->withInput();
            }
        }
        return Redirect::back()
            ->withErrors($validator)
            ->withInput();
    }

    public function ValidateUrl($url)
    {
        if (strpos($url, 'v=') !== false) {
                return explode("v=",$url)[1];
         }
         if (strpos($url, 'be/') !== false) {
                return explode("be/",$url)[1];
         }
        return $url;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LineaVideo  $lineaVideo
     * @return \Illuminate\Http\Response
     */
    public function show(LineaVideo $lineaVideo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LineaVideo  $lineaVideo
     * @return \Illuminate\Http\Response
     */
    public function edit(LineaVideo $id)
    {
        $data = Linea::withTranslation()->get();
        return Inertia::render('Dashboard/Lines/Videos/Edit', [
            'lines' => $data,
            'video' => $id,

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLineaVideoRequest  $request
     * @param  \App\Models\LineaVideo  $lineaVideo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LineaVideo $line)
    { 
        $validator = Validator::make($request->all(), [
            'tranlations.*.title' => 'required',
            'tranlations.*.content' => 'required',
            'linea_id' => 'required',
            'link' => 'required',
        ]);
       

        if ($validator->passes()) {
            try {

                $line->linea_id = $request["linea_id"];
                $line->link = $this->ValidateUrl($request["link"]);
                $line->slug = '';
                $line->save();
                foreach ($request["tranlations"] as $key => $value) {
                    DB::table("linea_video_translations")->where("linea_video_id", "=", $line->id)
                        ->where("locale", "=", $value['locale'])->update([
                        "title" => $value["title"],
                        "content" => $value["content"],
                    ]);
                }
                return Redirect::route('video');
            } catch (\Throwable $e) {
                return Redirect::route('video.edit', $line->id)
                    ->withErrors(json_encode($e->getMessage()))
                    ->withInput();
            }
        }
        return Redirect::route('video.edit', $line->id)
            ->withErrors($validator)
            ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LineaVideo  $lineaVideo
     * @return \Illuminate\Http\Response
     */
    public function destroy(LineaVideo $line)
    {
        try {
            $line->delete();
            return Redirect::back();
        } catch (\Throwable $e) {
            return Redirect::back()
            ->withErrors(json_encode($e->getMessage()))
            ->withInput();
        }
    }
}
