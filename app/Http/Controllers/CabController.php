<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Linea;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\HelperControllers;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Validator;

class CabController extends Controller
{


    public $dir_name_product = '/web/images/products/';

    public function index(Request $request)
    {
        $filter = ["search" => $request->search];


        $products = Product::filter($filter)
            ->with('translations', 'line')
            ->whereHas('line', function ($query) {
                $query->where('category_id', 2);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(env('PAGINATE'));

        return Inertia::render('Dashboard/Product/Cab/Index', [
            'filters' => $filter,
            'products' => $products
        ]);
    }

    public function create()
    {
        // $data = Linea::withTranslation('es')->get();
        $lines = Linea::where("category_id", 2)->get();
        return Inertia::render('Dashboard/Product/Cab/Create', [
            'lines' => $lines,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.name' => 'required',
            'tranlations.*.description' => 'required',
            'line_id' => 'required',
            'photo' => 'mimes:jpeg,jpg,png,webp|max:' . env('SIZE_FILE'),
        ]);

        if ($validator->passes()) {
            try {

                $data = [];
                $data["line_id"] = $request["line_id"];
                $data["subcategory_id"] = $request["category_id"];
                foreach ($request["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $product = Product::create($data);
                if ($request->hasfile('photo')) {
                    $path = $request->getSchemeAndHttpHost() . $this->dir_name_product;
                    $p = $request->file('photo');
                    $nameOrigin = $p->getClientOriginalName();
                    $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();
                    $url = $path . $name;
                    $p->move(public_path() . $this->dir_name_product, $name);
                    DB::table("products_image")->insert([
                        "product_id" => $product->id,
                        "url" => $url,
                        "name" => $name,
                    ]);
                }
                return Redirect::route("cab");
            } catch (\PDOException $e) {
                return Redirect::back()->withErrors(json_encode($e->getMessage()))
                    ->withInput();
            }
        }
        return Redirect::back()->withErrors($validator->errors()->all())
            ->withInput();
    }


    public function edit(Product $product)
    {
        $lines = Linea::where("category_id", 2)->get();
        return Inertia::render('Dashboard/Product/Cab/Edit', [
            'lines' => $lines,
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.name' => 'required',
            'tranlations.*.description' => 'required',
            'category_id' => 'required',
            'line_id' => 'required',
            'photo' => 'nullable|mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        if ($validator->passes()) {
            try {
                $product->slug = '';
                $product->subcategory_id  = $request->input("category_id");
                $product->line_id  = $request->input("line_id");
                $product->save();

                foreach ($request["tranlations"] as $key => $value) {
                    DB::table("product_translations")->where("product_id", "=", $product->id)
                        ->where("locale", "=", $value['locale'])->update([
                            "name" => $value["name"],
                            "description" => $value["description"],
                        ]);
                }

                if ($request->hasfile('photo')) {
                    /************************* delete file************ */
                    $img_name = DB::table("products_image")->where("product_id", "=", $product->id)->value('name');
                    $path = public_path($this->dir_name_product . $img_name);
                    $del = new HelperControllers();
                    $del->deleteFile($path);

                    //***********************************************/
                    $path = $request->getSchemeAndHttpHost() . $this->dir_name_product;
                    $p = $request->file('photo');
                    $nameOrigin = $p->getClientOriginalName();
                    $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();
                    $url = $path . $name;
                    $p->move(public_path() . $this->dir_name_product, $name);
                    DB::table("products_image")->where("product_id", "=", $product->id)
                        ->update([
                            "url" => $url,
                            "name" => $name,
                        ]);
                }
                return Redirect::route("cab");
            } catch (\Throwable $e) {
                \Log::debug($e);
                return Redirect::back()->withErrors(json_encode($e->getMessage()))
                    ->withInput();
            }
        }
        return Redirect::back()->withErrors($validator->errors()->all())
            ->withInput();
    }


    //****************************** images ******************************/
    public function ImagesIndex(Product $product)
    {
        return Inertia::render('Dashboard/Product/Cab/Image', [

            'images' => DB::table("products_image")->where("product_id", "=", $product->id)->get(),
            'product' => ["id" => $product->id, "name" => $product->name],
        ]);
    }

    public function ImagesStore(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [

            'photo' => 'required',
            'photo.*' => 'mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        $path = $request->getSchemeAndHttpHost() . $this->dir_name_product;

        try {
            if ($validator->passes()) {
                if ($request->hasfile('photo')) {
                    foreach ($request->file('photo') as $p) {
                        $nameOrigin = $p->getClientOriginalName();
                        $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();

                        $url = $path . $name;
                        $p->move(public_path() . $this->dir_name_product, $name);
                        DB::table("products_image")->insert(["name" => $name, "url" => $url, "product_id" => $product->id]);
                    }
                }
                return response()->json(['data' => $path, "msg" => "OK"]);
            }

            return response()->json(['error' => $validator->errors()->all()]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }


    public function ImageDestroy($id)
    {
        try {
            $name = DB::table('products_image')->where("id", "=", $id)->value('name');
            $path = public_path($this->dir_name_product . $name);

            if (File::exists($path)) {
                File::delete($path);
                // unlink($path);
                $im = DB::table('products_image')->where('id', '=', $id)->delete();
            }
            $im = DB::table('products_image')->where('id', '=', $id)->delete();

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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $del = new HelperControllers();
            $img_name = DB::table("products_image")->where("product_id", "=", $product->id)->value('name');
            $path = public_path($this->dir_name_product . $img_name);
            $del->deleteFile($path);
            $product->delete();
            return Redirect::route('cab');
        } catch (\Throwable $th) {
            return Redirect::route('cab')->withErrors(json_encode($th->getMessage()))->withInput();
        }
    }
}
