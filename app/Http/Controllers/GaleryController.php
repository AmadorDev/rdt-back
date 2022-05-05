<?php

namespace App\Http\Controllers;

use App\Models\Galery;
use App\Http\Controllers\HelperControllers;
use App\Models\Linea;
use App\Models\Product;
use App\Models\ProductEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Validator;
class GaleryController extends Controller
{
    public $dirname_image = '/web/images/news/galleries/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Dashboard/News/Galleries/Index', [
            
            'images' => Galery::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGaleryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
          
            'photo' => 'required',
            'photo.*' => 'mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        $path = $request->getSchemeAndHttpHost() . $this->dirname_image;

        try {
            if ($validator->passes()) {
                if ($request->hasfile('photo')) {
                    foreach ($request->file('photo') as $p) {
                        $nameOrigin = $p->getClientOriginalName();
                        $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();

                        $url = $path . $name;
                        $p->move(public_path() .$this->dirname_image, $name);
                        Galery::create(["name" => $name, "url" => $url]); 
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
     * Display the specified resource.
     *
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function show(Galery $galery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function edit(Galery $galery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGaleryRequest  $request
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGaleryRequest $request, Galery $galery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Galery $galery)
    {
        try {
          
            $path = public_path($this->dirname_image . $galery->name);
            
            if (File::exists($path)) {
                File::delete($path);
                // unlink($path);
                $galery->delete();
            }

            return redirect()->back();
        } catch (\Throwable $th) {
            return Redirect::back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }
}
