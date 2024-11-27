<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HelperControllers;
use App\Models\Linea;
use App\Models\Product;
use App\Models\ProductEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Validator;

class ProductEventController extends Controller
{
    public $dir_name_event = '/web/images/products/events/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ["search" => $request->search];
        return Inertia::render('Dashboard/Product/Events/Index', [
            'filters' => $filter,
            'events' => ProductEvent::filter($filter)->orderBy('created_at', 'desc')->paginate(env('PAGINATE')),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Product $product)
    {
       
        return Inertia::render('Dashboard/Product/Events/Create', [

            'product' => ["id" => $product->id, "name" => $product->name],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.title' => 'required',
            'tranlations.*.content' => 'required',
            'product_id' => 'required',
            'date_event' => 'required',
            'photo' => 'mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        if ($validator->passes()) {
            try {

                $data = [];
                $data["product_id"] = $request["product_id"];
                $data["date_event"] = $request["date_event"];
                foreach ($request["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $productEvent = ProductEvent::create($data);
                if ($request->hasfile('photo')) {
                    $path = $request->getSchemeAndHttpHost() . $this->dir_name_event;
                    $p = $request->file('photo');
                    $nameOrigin = $p->getClientOriginalName();
                    $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();

                    $url = $path . $name;
                    $p->move(public_path() . $this->dir_name_event, $name);
                    $prodEvent = ProductEvent::find($productEvent->id);

                    $prodEvent->url = $url;
                    $prodEvent->url_name = $name;
                    $prodEvent->save();
                }
                return Redirect::route('product_event');
            } catch (\Throwable $e) {
                \Log::debug($e);
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
     * @param  \App\Models\ProductEvent  $productEvent
     * @return \Illuminate\Http\Response
     */
    public function show(ProductEvent $productEvent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductEvent  $productEvent
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductEvent $productEvent)
    {
        return Inertia::render('Dashboard/Product/Events/Edit', [
            'event' => $productEvent,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductEventRequest  $request
     * @param  \App\Models\ProductEvent  $productEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductEvent $productEvent)
    {
        $validator = Validator::make($request->all(), [

            'tranlations.*.title' => 'required',
            'tranlations.*.content' => 'required',
            'date_event' => 'required',
            'photo' => 'nullable|mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        if ($validator->passes()) {
            try {
                $productEvent->date_event = $request["date_event"];
                $productEvent->slug = '';
                $productEvent->save();

                foreach ($request["tranlations"] as $key => $value) {
                    DB::table("product_event_translations")->where("product_event_id", "=", $productEvent->id)
                        ->where("locale", "=", $value['locale'])->update([
                        "title" => $value["title"],
                        "content" => $value["content"],
                    ]);
                }

                if ($request->hasfile('photo')) {

                    //TODO:************************* delete file ************ */
                    //?:**************************delete file update photo*** */

                    $path = public_path($this->dir_name_event . $productEvent->url_name);
                    $del = new HelperControllers();
                    $del->deleteFile($path);

                    //**********************************************************/
                    $path = $request->getSchemeAndHttpHost() . $this->dir_name_event;
                    $p = $request->file('photo');
                    $nameOrigin = $p->getClientOriginalName();
                    $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();

                    $url = $path . $name;
                    $p->move(public_path() . $this->dir_name_event, $name);

                    $productEvent->url = $url;
                    $productEvent->url_name = $name;
                    $productEvent->save();
                }
                return Redirect::route('product_event');
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
     * @param  \App\Models\ProductEvent  $productEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductEvent $productEvent)
    {
        try {
            /************************* delete file************ */
            $path = public_path($this->dir_name_event . $productEvent->url_name);
            $del = new HelperControllers();
            if ($del->deleteFile($path)) {
                $productEvent->delete();
                return Redirect::back();
            };
            return Redirect::back()
                ->withErrors('Error en el servidor')
                ->withInput();

        } catch (\Throwable $th) {
            return Redirect::back()
                ->withErrors(json_encode($th->getMessage()))
                ->withInput();
        }
    }
}
