<?php

namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\Linea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Validator;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {$filter = ["q" => $request->q];
        return Inertia::render('Dashboard/Lines/Infos/Index', [
            'filters' => $filter,
            'hair_types' => Info::filter($filter)
            ->join("lineas","infos.linea_id","=","lineas.id")
            ->select("infos.*","lineas.slug as line")
            ->orderBy('created_at', 'desc')->paginate(env('PAGINATE')),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Linea $line)
    {
        $hair_type = Info::where("linea_id","=",$line->id)->count();
        if($hair_type > 0){
            return redirect()->route("hair_type");
        }
        return Inertia::render('Dashboard/Lines/Infos/Create', [
            'line' => ["id" => $line->id, "name" => $line->name],
        ]);
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
            'tranlations.*.content' => 'required',
            'line' => 'required',
        ]);
        if ($validator->passes()) {
            try {
                $data = [];
                $data["linea_id"] = $request["line"];
                foreach ($request["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $info = Info::create($data);
                return Redirect::route('hair_type');

            } catch (\Throwable$e) {
                return Redirect::back()
                    ->withErrors(json_encode($e->getMessage()))
                    ->withInput();
            }
        }
        return Redirect::back()
            ->withErrors($validator)
            ->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Info $info)
    {
        return Inertia::render('Dashboard/Lines/Infos/Edit', [
            'hair_type' => $info,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.content' => 'required',
        ]);
        if ($validator->passes()) {
            try {

                foreach ($request["tranlations"] as $key => $value) {
                    DB::table("info_translations")->where("info_id", "=", $id)
                        ->where("locale", "=", $value['locale'])->update([
                        "content" => $value["content"],
                        // "content" => $value["content"],
                    ]);
                }
                return Redirect::route('hair_type');

            } catch (\Throwable$e) {
                return Redirect::back()
                    ->withErrors(json_encode($e->getMessage()))
                    ->withInput();
            }
        }
        return Redirect::back()
            ->withErrors($validator)
            ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Info $info)
    {
        try {
           
            $info->delete();
            DB::table("info_translations")->where("info_id","=",$info->id)->delete();
            return Redirect::back();
        } catch (\Throwable$th) {
            return Redirect::back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }
}
