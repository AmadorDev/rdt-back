<?php

namespace App\Http\Controllers;

use App\Models\PersonRegister;
use App\Http\Requests\StorePersonRegisterRequest;
use App\Http\Requests\UpdatePersonRegisterRequest;

class PersonRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StorePersonRegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePersonRegisterRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PersonRegister  $personRegister
     * @return \Illuminate\Http\Response
     */
    public function show(PersonRegister $personRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PersonRegister  $personRegister
     * @return \Illuminate\Http\Response
     */
    public function edit(PersonRegister $personRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePersonRegisterRequest  $request
     * @param  \App\Models\PersonRegister  $personRegister
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePersonRegisterRequest $request, PersonRegister $personRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PersonRegister  $personRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy(PersonRegister $personRegister)
    {
        //
    }
}
