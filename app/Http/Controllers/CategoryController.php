<?php

namespace App\Http\Controllers;

use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use \Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   $filter = ["search" => $request->search];
        return Inertia::render('Dashboard/Category/Index', [
             'filters' => $filter,
             'categories' => Category::filter($filter)->orderBy('created_at','desc')->paginate(env('PAGINATE')),
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return Inertia::render('Dashboard/Category/Create', [
            // 'filters' => $filter,
            // 'news' => News::filter($filter)->orderBy('created_at','desc')->paginate(env('PAGINATE')),
            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'max:50', 'unique:categories,name'],
            
        ]);

        if ($validator->fails()) {
            return Redirect::route('category.add')
                ->withErrors($validator)
                ->withInput();
        } else {
            try {
                $cat = new Category();
                $cat->name =strtoupper( $request->name);
                $cat->description = $request->description;
                $cat->save();
                return Redirect::route('category');
            } catch (Exception $e) {
                
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Inertia::render('Dashboard/Category/Edit', [
            'category' => Category::find($id),
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'max:50', Rule::unique('categories')->ignore($id)],
            
        ]);
        $cat =  Category::find($id);
        if ($validator->fails()) {
            return Redirect::route('category.edit',$cat->id)
                ->withErrors($validator)
                ->withInput();
        } else {
            try {
                
                $cat->name =strtoupper($request->name);
                $cat->description = $request->description;
                $cat->save();
                return Redirect::route('category');
            } catch (Exception $e) {
                
            }

        }
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        

        try {
            $cate = Category::find($id);
            $cate->delete();
            return Redirect::route('category');
        } catch (\Throwable $th) {
            return Redirect::back()
            ->withErrors($th->getMessage())
            ->withInput();
        }
    }
}
