<?php

namespace App\Http\Controllers;

use App\Models\ProductVideo;
use App\Models\Linea;
use App\Models\Product;
use App\Models\ProductEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Validator;

class ProductVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ["search" => $request->search];
        return Inertia::render('Dashboard/Product/Videos/Index', [
            'filters' => $filter,
            'videos' => ProductVideo::filter($filter)->orderBy('created_at', 'desc')->paginate(env('PAGINATE')),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Product $product)
    {
       
        return Inertia::render('Dashboard/Product/Videos/Create', [

            'product' => ["id" => $product->id, "name" => $product->name],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductVideoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.title' => 'required',
            'tranlations.*.content' => 'required',
            'product_id' => 'required',
            'link' => 'required|url',

        ]);
       

        if ($validator->passes()) {
            try {
                
                $data = [];
                $data["product_id"] = $request["product_id"];
                $data["link"] = $request["link"];
                foreach ($request["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $linea = ProductVideo::create($data);
                return Redirect::route('product_video');
            } catch (\Throwable $e) {

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
     * @param  \App\Models\ProductVideo  $productVideo
     * @return \Illuminate\Http\Response
     */
    public function show(ProductVideo $productVideo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductVideo  $productVideo
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductVideo $productVideo)
    {
        
        return Inertia::render('Dashboard/Product/Videos/Edit', [
            'video' => $productVideo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductVideoRequest  $request
     * @param  \App\Models\ProductVideo  $productVideo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductVideo $productVideo)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.title' => 'required',
            'tranlations.*.content' => 'required',
            'link' => 'required|url',
        ]);
       

        if ($validator->passes()) {
            try {

                
                $productVideo->link = $request["link"];
                $productVideo->slug = '';
                $productVideo->save();
                foreach ($request["tranlations"] as $key => $value) {
                    DB::table("product_video_translations")->where("product_video_id", "=", $productVideo->id)
                        ->where("locale", "=", $value['locale'])->update([
                        "title" => $value["title"],
                        "content" => $value["content"],
                    ]);
                }
                return Redirect::route('product_video');
            } catch (\Throwable $e) {
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
     * @param  \App\Models\ProductVideo  $productVideo
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductVideo $productVideo)
    {
        try {
            $productVideo->delete();
            return Redirect::back();
        } catch (\Throwable $e) {
            return Redirect::back()
            ->withErrors(json_encode($e->getMessage()))
            ->withInput();
        }
    }
}
