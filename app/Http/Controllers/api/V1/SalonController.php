<?php

namespace App\Http\Controllers\api\V1;


use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Controllers\Controller;

class SalonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSalon(Request $request)
    {
        \App::setLocale($request->locale);
        $tran = [
            "title"=>trans('api.salon.title'),
            "shortname"=>trans('api.salon.shortname')
        ];
        try {
            $salon = Salon::all();
            return response()->json(["data" => $salon, "status" => "OK",'translation'=> $tran],200);
        } catch (Exception $e) {
            return response()->json(["message" => $e],500);
        }
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
     * @param  \App\Http\Requests\StoreSalonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalonRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salon  $salon
     * @return \Illuminate\Http\Response
     */
    public function show(Salon $salon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salon  $salon
     * @return \Illuminate\Http\Response
     */
    public function edit(Salon $salon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSalonRequest  $request
     * @param  \App\Models\Salon  $salon
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalonRequest $request, Salon $salon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salon  $salon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salon $salon)
    {
        //
    }
}
