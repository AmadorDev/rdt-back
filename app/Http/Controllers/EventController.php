<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Controllers\HelperControllers;
use App\Models\Linea;
use App\Models\Product;
use App\Models\ProductEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Validator;

class EventController extends Controller
{
    public $dir_name_event = '/web/images/news/events/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ["search" => $request->search];
        return Inertia::render('Dashboard/News/Events/Index', [
            'filters' => $filter,
            'events' => Event::filter($filter)->orderBy('created_at', 'desc')->paginate(env('PAGINATE')),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Dashboard/News/Events/Create', [

            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.title' => 'required',
            'tranlations.*.content' => 'required',
           
            'date_event' => 'required',
            'photo' => 'mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        if ($validator->passes()) {
            try {

                $data = [];
            
                $data["date_event"] = $request["date_event"];
                foreach ($request["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $event = Event::create($data);
                if ($request->hasfile('photo')) {
                    $path = $request->getSchemeAndHttpHost() . $this->dir_name_event;
                    $p = $request->file('photo');
                    $nameOrigin = $p->getClientOriginalName();
                    $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();

                    $url = $path . $name;
                    $p->move(public_path() . $this->dir_name_event, $name);
                

                    $event->url = $url;
                    $event->url_name = $name;
                    $event->save();
                }
                return Redirect::route('new_event');
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
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        return Inertia::render('Dashboard/News/Events/Edit', [
            'event' => $event,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [

            'tranlations.*.title' => 'required',
            'tranlations.*.content' => 'required',
            'date_event' => 'required',
            'photo' => 'nullable|mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        if ($validator->passes()) {
            try {
                $event->date_event = $request["date_event"];
                $event->slug = '';
                $event->save();

                foreach ($request["tranlations"] as $key => $value) {
                    DB::table("event_translations")->where("event_id", "=", $event->id)
                        ->where("locale", "=", $value['locale'])->update([
                        "title" => $value["title"],
                        "content" => $value["content"],
                    ]);
                }

                if ($request->hasfile('photo')) {

                    //TODO:************************* delete file ************ */
                    //?:**************************delete file update photo*** */

                    $path = public_path($this->dir_name_event . $event->url_name);
                    $del = new HelperControllers();
                    $del->deleteFile($path);

                    //**********************************************************/
                    $path = $request->getSchemeAndHttpHost() . $this->dir_name_event;
                    $p = $request->file('photo');
                    $nameOrigin = $p->getClientOriginalName();
                    $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();

                    $url = $path . $name;
                    $p->move(public_path() . $this->dir_name_event, $name);

                    $event->url = $url;
                    $event->url_name = $name;
                    $event->save();
                }
                return Redirect::route('new_event');
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
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        try {
            /************************* delete file************ */
            $path = public_path($this->dir_name_event . $event->url_name);
            $del = new HelperControllers();
            if ($del->deleteFile($path)) {
                $event->delete();
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
