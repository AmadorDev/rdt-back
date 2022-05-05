<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Linea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Validator;

class LineaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ["search" => $request->search];
        return Inertia::render('Dashboard/Lines/Index', [
            'filters' => $filter,
            'lines' => Linea::filter($filter)->with('translations','category')
            ->orderBy('created_at', 'desc')->paginate(env('PAGINATE')),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Dashboard/Lines/Create', [

            'categories' => Category::all(),

        ]);
    }
    public function image($id)
    {
        return Inertia::render('Dashboard/Lines/Image', [

            'images' => DB::table("lineas_image")->where("linea_id", "=", $id)->get(),
            'line' => $id,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLineaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'tranlations.*.name' => 'required',
            'tranlations.*.description' => 'required',
            'featured' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->passes()) {
            try {
               
                $data = [];
                $data["category_id"] = $request["category_id"];
                $data["featured"] = $request["featured"];
                foreach ($request["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $linea = Linea::create($data);
                return Redirect::route('line');
            } catch (\Exception $e) {

                return Redirect::route('line.add')
                    ->withErrors($e)
                    ->withInput();
            }
        }
        return Redirect::route('line.add')
            ->withErrors($validator)
            ->withInput();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function show(Linea $linea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Inertia::render('Dashboard/Lines/Edit', [

            'categories' => Category::all(),
            'line' => Linea::find($id),

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLineaRequest  $request
     * @param  \App\Models\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [

            'tranlations.*.name' => 'required',
            'tranlations.*.description' => 'required',
            'featured' => 'required',
            'category_id' => 'required',
        ]);
        \Log::debug($request);

        if ($validator->passes()) {
            try {
               
                $linea = Linea::find($id);
                $linea->featured=$request["featured"];
                $linea->slug = '';
                $linea->save();
                foreach ($request["tranlations"] as $key => $value) {
                    DB::table("linea_translations")->where("linea_id", "=", $id)
                        ->where("locale", "=", $value['locale'])->update([
                        "name" => $value["name"],
                        "description" => $value["description"],
                    ]);
                }

                return Redirect::route('line');
            } catch (\Exception $e) {
                \Log::debug($e);
                return Redirect::route('line.edit', $id)
                    ->withErrors(json_encode($e))
                    ->withInput();
            }
        }
        return Redirect::route('line.edit', $id)
            ->withErrors($validator)
            ->withInput();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $line = Linea::find($id);
            $line->delete();
            return redirect()->back();
        } catch (\Throwable $th) {
            return Redirect::route('line')
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }
    public function destroyImage($id)
    {
        try {
            $name = DB::table('lineas_image')->where("id", "=", $id)->value('name');
            $path = public_path("/web/images/lineas/" . $name);
            
            if (File::exists($path)) {
                File::delete($path);
                // unlink($path);
                $im = DB::table('lineas_image')->where('id', '=', $id)->delete();
            }

            return redirect()->back();
        } catch (\Throwable $th) {
            return Redirect::back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }

    public function updateImageCover(Request $request, $id)
    {
        
        try {
            
            DB::table("lineas_image")->where("linea_id","=",$request->line)->update(["cover"=>0]);
            DB::table("lineas_image")->where("id","=",$id)->update(["cover"=>$request["cover"]]);
            return Redirect::back();
        } catch (\Throwable $th) {
            return Redirect::back()
            ->withErrors($th->getMessage())
            ->withInput();
        }
    }
}
