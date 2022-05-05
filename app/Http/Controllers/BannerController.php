<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Validator;

class BannerController extends Controller
{
    public $dirname_image = '/web/images/banners/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Dashboard/Banner/Index', [
            'images' => Banner::all(),
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
     * @param  \App\Http\Requests\StoreBannerRequest  $request
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
                        Banner::create(["name" => $name, "url" => $url]); 
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
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBannerRequest  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        //
    }

    public function status( Banner $banner)
    {
        try {
            $banner->status = !$banner->status;
            $banner->save();
            return redirect()->back();
        } catch (\Throwable $th) {
            return Redirect::back()
            ->withErrors($th->getMessage())
            ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        try {
          
            $path = public_path($this->dirname_image . $banner->name);
            if (File::exists($path)) {
                File::delete($path);
                // unlink($path);
                $banner->delete();
            }

            return redirect()->back();
        } catch (\Throwable $th) {
            return Redirect::back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }
}
