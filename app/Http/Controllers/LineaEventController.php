<?php

namespace App\Http\Controllers;

use App\Models\Linea;
use App\Models\LineaEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Validator;

class LineaEventController extends Controller
{

    public $dir_name_event = '/web/images/lineas/event/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ["search" => $request->search];
        return Inertia::render('Dashboard/Lines/Events/Index', [
            'filters' => $filter,
            'events' => LineaEvent::filter($filter)->orderBy('created_at', 'desc')->paginate(env('PAGINATE')),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Linea $line)
    {
        return Inertia::render('Dashboard/Lines/Events/Create', [
            'line' => ["id" => $line->id, "name" => $line->name],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLineaEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.title' => 'required',
            'linea_id' => 'required',
            'photo.*' => 'mimes:jpeg,jpg,png|max:' . env('SIZE_FILE'),
        ]);

        if ($validator->passes()) {
            try {

                $data = [];
                $data["linea_id"] = $request["linea_id"];
                foreach ($request["tranlations"] as $key => $value) {
                    $data[$value["locale"]] = $value;
                }
                $linea = LineaEvent::create($data);
                $fils = $this->storeImagenes($request, $linea->id);
                \Log::debug($fils);

                return Redirect::route('event');

            } catch (\Throwable$e) {
                return Redirect::back()
                    ->withErrors(json_encode($e->getMessage()))
                    ->withInput();
            }
        }
        return Redirect::back()
            ->withErrors($validator)
            ->withInput();
    }

    public function storeImagenes($request, $id)
    {
        try {
            $path = $request->getSchemeAndHttpHost() . $this->dir_name_event;
            foreach ($request->file('photo') as $p) {
                $nameOrigin = $p->getClientOriginalName();
                $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();
                $url = $path . $name;
                $p->move(public_path() . $this->dir_name_event, $name);
                DB::table("testing_images")->insert(["name" => $name, "url" => $url, "linea_event_id" => $id]);
            }
            return 1;
        } catch (\Throwable$th) {
            \Log::debug($th->getMessage());
            return 0;
        }

    }

    public function edit($id)
    {$data = Linea::withTranslation()->get();
        return Inertia::render('Dashboard/Lines/Events/Edit', [
            'lines' => $data,
            'event' => LineaEvent::find($id),

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLineaEventRequest  $request
     * @param  \App\Models\LineaEvent  $lineaEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.title' => 'required',
            'linea_id' => 'required',
        ]);

        if ($validator->passes()) {
            try {

                $linea = LineaEvent::find($id);
                $linea->linea_id = $request["linea_id"];
                $linea->date_event = $request["date_event"];
                $linea->slug = '';
                $linea->save();
                foreach ($request["tranlations"] as $key => $value) {
                    DB::table("linea_event_translations")->where("linea_event_id", "=", $id)
                        ->where("locale", "=", $value['locale'])->update([
                        "title" => $value["title"],
                        // "content" => $value["content"],
                    ]);
                }

                if ($request->hasfile('photo')) {
                    $path = $request->getSchemeAndHttpHost() . $this->dir_name_event;
                    $p = $request->file('photo');
                    $nameOrigin = $p->getClientOriginalName();
                    $name = rand(0, 100) . time() . '.' . $p->getClientOriginalExtension();

                    $url = $path . $name;
                    $p->move(public_path() . $this->dir_name_event, $name);

                    $linea->url = $url;
                    $linea->url_name = $name;
                    $linea->save();
                }

                return Redirect::route('event');
            } catch (\Exception$e) {
                \Log::debug($e);
                return Redirect::route('event.edit', $id)
                    ->withErrors(json_encode($e))
                    ->withInput();
            }
        }
        return Redirect::route('event.edit', $id)
            ->withErrors($validator)
            ->withInput();
    }

    public function ImageIndex(LineaEvent $lineEvent)
    {
        return Inertia::render('Dashboard/Lines/Events/Image', [

            'images' => DB::table("testing_images")->where("linea_event_id", "=", $lineEvent->id)->get(),
            'lineEvent' => ["id" => $lineEvent->id, "name" => $lineEvent->title],
        ]);
    }

    public function ImageStore(Request $request, LineaEvent $lineEvent)
    {
        $validator = Validator::make($request->all(), [
            'tranlations.*.title' => 'required',
            'linea_id' => 'required',
        ]);

        if ($validator->passes()) {
            try {
                $fils = $this->storeImagenes($request, $lineEvent->id);
                return Redirect::back();
            } catch (\Throwable$th) {
                return Redirect::back()->withErrors(json_encode($th->getMessage()))->withInput();

            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();

        }

    }

    public function ImageDestroy($imageId)
    {
        try {
            $del = new HelperControllers();
            $img_name = DB::table("testing_images")->where("id", "=", $imageId)->value('name');
            $path = public_path($this->dir_name_event . $img_name);
            $del->deleteFile($path);

            DB::table("testing_images")->where("id", "=", $imageId)->delete();
            return Redirect::back();
        } catch (\Throwable$th) {
            return Redirect::back()->withErrors(json_encode($th->getMessage()))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LineaEvent  $lineaEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $ev = LineaEvent::find($id);
            $ev->delete();
            return Redirect::back();
        } catch (\Throwable$th) {
            return Redirect::back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }
}
