<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Galery;
use App\Models\Banner;
use App\Models\LatestRelease;
use App\Models\Novelty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewController extends Controller
{

    public function getNews(Request $request)
    {
        \App::setLocale($request->locale);
        try {

            $news = Novelty::translatedIn($request->locale)->get();
            $_dt = [];
            $image_base = $request->getSchemeAndHttpHost() . env('LOGO_NOT');
            foreach ($news as $k => $v) {

                $image = DB::table('novelty_image')->where("novelty_id", "=", $v->id)->where("cover", "=", 1)->value('url');
                array_push($_dt, [
                    "content" => $v->content,
                    "id" => $v->id,
                    "image" => $image ? $image : $image_base,
                    "slug" => $v->slug,
                    "title" => $v->title,
                ]);
            }

            return response()->json(["rows" => count($news), "data" => $_dt, 'translation' => trans('api.news')]);
        } catch (\Throwable $th) {
            return response()->json(["rows" => count($news)]);
        }
    }

    public function getNewsDetail(Request $request, $slug)
    {
        \App::setLocale($request->locale);

        try {

            $new = Novelty::where('slug', "=", $slug)->get();
            if (count($new) > 0) {
                $_dt = [];
                $image_base = $request->getSchemeAndHttpHost() . env('LOGO_NOT');
                foreach ($new as $k => $v) {

                    $image = DB::table('novelty_image')->where("novelty_id", "=", $v->id)->where("cover", "=", 1)->value('url');
                    array_push($_dt, [
                        "content" => $v->content,
                        "id" => $v->id,
                        "image" => $image ? $image : $image_base,
                        "slug" => $v->slug,
                        "title" => $v->title,
                    ]);
                }
                $images = DB::table('novelty_image')->where("novelty_id", "=", $new[0]->id)->where("cover", "=", 0)->get('url');

                return response()->json(["rows" => count($new), "data" => $_dt[0], 'images' => $images]);
            } else {
                return response()->json(["rows" => 0]);
            }
        } catch (\Throwable $th) {
            \Log::debug($th);
            return response()->json(["rows" => 0]);
        }
    }

    ///****************************************** events**************************** */
    public function getEvents(Request $request)
    {
        \App::setLocale($request->locale);
        try {

            $news = Event::translatedIn($request->locale)->get();
            $_dt = [];

            foreach ($news as $k => $v) {

                array_push($_dt, [
                    "content" => $v->content,
                    "id" => $v->id,
                    "image" => $v->url,
                    "slug" => $v->slug,
                    "title" => $v->title,
                    "images"=>\DB::table("event_images")->where("event_id","=",$v->id)->get()
                ]);
            }

            return response()->json(["rows" => count($news), "data" => $_dt]);
        } catch (\Throwable $th) {
            return response()->json(["rows" => count($news)]);
        }
    }
    /********************************************** galleries */
    public function getGalleries(Request $request)
    {
        try {
            $galleries = Galery::all();
            return response()->json(["rows" => count($galleries), "data" => $galleries]);
        } catch (\Throwable $th) {
            return response()->json(["rows" => 0]);
        }
    }

    /****************************************** events **********************/
    public function getDetailEvent(Request $request, $slug)
    {
        try {
            \App::setLocale($request->locale);
            $event = Event::where("slug", "=", $slug)->get();
            return response()->json(["rows" => count($event), "data" => count($event) > 0 ? $event[0] : []]);
        } catch (\Throwable $th) {
            return response()->json(["rows" => 0]);
        }
    }

    /****************************************** latest **********************/
    public function getLatest(Request $request)
    {
        try {
            \App::setLocale($request->locale);
            $latest = LatestRelease::all();

            return response()->json(["rows" => count($latest), "data" => $latest]);
        } catch (\Throwable $th) {

            return response()->json(["rows" => 0]);
        }
    }

     /****************************************** latest **********************/
     public function getBannerDefault(Request $request)
     {
         try {
             \App::setLocale($request->locale);
             $banner = Banner::where("status","=",1)->get();
 
             return response()->json(["rows" => count($banner), "data" => $banner]);
         } catch (\Throwable $th) {
 
             return response()->json(["rows" => 0]);
         }
     }
}
