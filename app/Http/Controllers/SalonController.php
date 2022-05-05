<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SalonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ["search" => $request->search];
        return Inertia::render('Dashboard/Salons/Index', [
             'filters' => $filter,
             'salons' => Salon::filter($filter)->orderBy('created_at','desc')->paginate(env('PAGINATE')),
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Dashboard/Salons/Create', [
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSalonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'max:50'],
            'district' => ['required'],
            'city' => ['required'],
            'address' => ['required'],
            'country' => ['required'],
            'lat' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            try {
                $salon = new Salon();
                $salon->name = strtoupper($request->name);
                $salon->district = $request->district;
                $salon->city = $request->city;
                $salon->address = $request->address;
                $salon->country = $request->country;
                $salon->lat = $request->lat;
                $salon->lng = $request->lng;
                $salon->save();
                return Redirect::route('salon');
            } catch (\Throwable $e) {
                return Redirect::back()->withErrors(json_encode($e->getMessage()))->withInput();
            }

        }
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
        return Inertia::render('Dashboard/Salons/Edit', [
            'salon' => $salon,
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSalonRequest  $request
     * @param  \App\Models\Salon  $salon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salon $salon)
    {
        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'max:50'],
            'district' => ['required'],
            'city' => ['required'],
            'address' => ['required'],
            'country' => ['required'],
            'lat' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            try {
                
                $salon->name = strtoupper($request->name);
                $salon->district = $request->district;
                $salon->city = $request->city;
                $salon->address = $request->address;
                $salon->country = $request->country;
                $salon->lat = $request->lat;
                $salon->lng = $request->lng;
                $salon->save();
                return Redirect::route('salon');
            } catch (\Throwable $e) {
                return Redirect::back()->withErrors(json_encode($e->getMessage()))->withInput();
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salon  $salon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salon $salon)
    {
        try {
            
            $salon->delete();
            return Redirect::route('salon');
        } catch (\Throwable $th) {
            return Redirect::back()
            ->withErrors(json_encode($th->getMessage()))
            ->withInput();
        }
    }
}
