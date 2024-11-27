<?php

namespace App\Http\Controllers;

use App\Models\LatestRelease;
use App\Http\Controllers\HelperControllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Validator;

class LatestReleaseController extends Controller
{
    public $dirname_image = '/web/images/news/latest/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Dashboard/News/Latest/Index', [
            
            'images' => LatestRelease::all(),
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
     * @param  \App\Http\Requests\StoreLatestReleaseRequest  $request
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
                        LatestRelease::create(["name" => $name, "url" => $url]); 
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
     * @param  \App\Models\LatestRelease  $latestRelease
     * @return \Illuminate\Http\Response
     */
    public function show(LatestRelease $latestRelease)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LatestRelease  $latestRelease
     * @return \Illuminate\Http\Response
     */
    public function edit(LatestRelease $latestRelease)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLatestReleaseRequest  $request
     * @param  \App\Models\LatestRelease  $latestRelease
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLatestReleaseRequest $request, LatestRelease $latestRelease)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LatestRelease  $latestRelease
     * @return \Illuminate\Http\Response
     */
    public function destroy(LatestRelease $latest)
    {
        try {
          
            $path = public_path($this->dirname_image . $latest->name);
            
            if (File::exists($path)) {
                File::delete($path);
                // unlink($path);
                $latest->delete();
            }

            return redirect()->back();
        } catch (\Throwable $th) {
            return Redirect::back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }
}
