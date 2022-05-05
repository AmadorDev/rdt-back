<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\PersonRegister;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class PersonRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
    public function store(Request $request)
    {
        \App::setLocale($request->locale);

        $message = trans('api.email.success');
        $messageError = trans('api.email.error');

        $validator = Validator::make($request->all(), [
            'email' => ['required','email', Rule::unique('person_registers')],
        ]);
        \Log::debug($request);

        if ($validator->passes()) {
            try {
                $em = new PersonRegister();
                $em->email = $request->email;
                $em->save();
                return response()->json(["message" => $message], 201);
            } catch (\Throwable $th) {
                \Log::debug($th);
                return response()->json(["error" => $messageError], 400);
            }
        }

        return response()->json(["error"=>$validator->errors()->all()]);

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
