<?php

namespace App\Http\Controllers;

use App\Models\Novelty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Validator;

use App\Http\Controllers\HelperControllers;

class NoveltyController extends Controller
{
    public $dir_name_novelty = '/web/images/news/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ["search" => $request->search];
        return Inertia::render('Dashboard/News/Index', [
            'filters' => $filter,
            'news' => Novelty::filter($filter)->orderBy('created_at', 'desc')->paginate(env('PAGINATE')),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Dashboard/News/Create', [
        ]);
    }

    public function images(Novelty $novelty)
    {
        return Inertia::render('Dashboard/News/Image', [
            'images' => DB::table("novelty_image")->where("novelty_id", "=", $novelty->id)->get(),
            "novelty"=>["id"=>$novelty->id,"title"=>$novelty->title]
        ]);
    }


    //*?:--------------------- images ----------------------------------


    public function setImages(Request $request, Novelty $novelty)
    {
        $validator = Validator::make($request->all(), [
            
            'photo'    => 'required',
            'photo.*'  => 'mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        $path = $request->getSchemeAndHttpHost() . $this->dir_name_novelty;

        try {
            if ($validator->passes()) {
                if ($request->hasfile('photo')) {
                    foreach ($request->file('photo') as $p) {
                        $nameOrigin = $p->getClientOriginalName();
                        $name       = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();
                        $url = $path . $name;
                        $p->move(public_path() . $this->dir_name_novelty, $name);

                        DB::table("novelty_image")
                            ->insert(["name" => $name, "url" => $url, "novelty_id" => $novelty->id]);
                    }

                }
                return response()->json(['data' => $path, "msg" => "OK"]);
            }

            return response()->json(['error' => $validator->errors()->all()]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    public function destroyImage(Request $request, $id)
    {
        try {
            $name = DB::table('novelty_image')->where("id", "=", $id)->value('name');
            $path = public_path($this->dir_name_novelty . $name);
            $delImg = new HelperControllers();
            if($delImg->deleteFile($path)){
                $name = DB::table('novelty_image')->where("id", "=", $id)->delete();
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            return Redirect::back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }

    public function updateCover(Request $request, $id)
    {
        
        try {
            
            DB::table("novelty_image")->where("novelty_id","=",$request->novelty)->update(["cover"=>0]);
            DB::table("novelty_image")->where("id","=",$id)->update(["cover"=>$request["cover"]]);
            return Redirect::back();
        } catch (\Throwable $th) {
            return Redirect::back()
            ->withErrors($th->getMessage())
            ->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNoveltyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.title' => 'required',
            'tranlations.*.content' => 'required',
        ]);
        if ($validator->passes()) {
            try {
                $data = [];
                foreach ($request["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $product = Novelty::create($data);

                return Redirect::route("new");
            } catch (\PDOException $e) {
                return Redirect::back()->withErrors(json_encode($e->getMessage()))
                    ->withInput();
            }
        }
        return Redirect::back()->withErrors($validator->errors()->all())
            ->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Novelty  $novelty
     * @return \Illuminate\Http\Response
     */
    public function show(Novelty $novelty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Novelty  $novelty
     * @return \Illuminate\Http\Response
     */
    public function edit(Novelty $novelty)
    {
        
        return Inertia::render('Dashboard/News/Edit', [
          
            'novelty' => $novelty,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNoveltyRequest  $request
     * @param  \App\Models\Novelty  $novelty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Novelty $novelty)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.title' => 'required',
            'tranlations.*.content' => 'required',
        ]);
        if ($validator->passes()) {
            try {
                $novelty->slug = '';
                $novelty->save();
                foreach ($request["tranlations"] as $key => $value) {
                    DB::table("novelty_translations")->where("novelty_id", "=", $novelty->id)
                        ->where("locale", "=", $value['locale'])->update([
                        "title" => $value["title"],
                        "content" => $value["content"],
                    ]);
                }

                return Redirect::route("new");
            } catch (\PDOException $e) {
                return Redirect::back()->withErrors(json_encode($e->getMessage()))
                    ->withInput();
            }
        }
        return Redirect::back()->withErrors($validator->errors()->all())
            ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Novelty  $novelty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Novelty $novelty)
    {
        try {
            $novelty->delete();
            return Redirect::back();
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors(json_encode($th->getMessage()))
            ->withInput();
        }
    }
}
