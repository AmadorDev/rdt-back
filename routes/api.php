<?php

use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\V1\CategoryController;
use App\Http\Controllers\api\V1\GeneralController;
use App\Http\Controllers\api\V1\LineaController;
use App\Http\Controllers\api\V1\NewController;
use App\Http\Controllers\api\V1\PersonRegisterController;
use App\Http\Controllers\api\V1\ProductController;
use App\Http\Controllers\api\V1\SalonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {

//**************end salons********************

//******************categories*********************
    Route::get("categories", [CategoryController::class, "index"])->name("api.cate");
    Route::post("categories", [CategoryController::class, "store"]);

//**************lineas********************
    Route::get("lineas", [LineaController::class, "index"]);

    Route::post("lineas", [LineaController::class, "store"]);
    Route::post("lineas/files", [LineaController::class, "storeFiles"]);

    Route::post("lineas/events", [LineaController::class, "storeEvent"]);
    Route::post("lineas/videos", [LineaController::class, "storeVideo"]);

//****************** products *********************
    Route::get("products", [ProductController::class, "index"]);

    Route::post("products", [ProductController::class, "store"]);
    Route::post("products/files", [ProductController::class, "storeFiles"]);

    // ------event product------
    Route::post("products/events", [ProductController::class, "storeEvent"]);

    // ------event video------
    Route::post("products/videos", [ProductController::class, "storeVideo"]);

//****************** users *********************
    Route::post("users/register", [AuthController::class, "register"]);
    Route::post("users/login", [AuthController::class, "login"]);

    Route::group(['middleware' => ['locale']], function () {
        //?:register email--------------------
        Route::post("email", [PersonRegisterController::class, "store"]);

        //******************salons*********************
        Route::get("salons", [SalonController::class, "getSalon"])->name("api.salon");

        //************************* MENU LINEAS ********************
        Route::get("menu/linea", [GeneralController::class, "getMenuLinea"]);
        Route::get("menu/lines/tree/{id}", [GeneralController::class, "getMenuTree"]);
        //************************* END LINEAS ********************

        Route::get("lineas/{slug}", [LineaController::class, "detail"]);
        Route::get("lineas/features/all", [LineaController::class, "Featureds"]);
        Route::get("lineas/events/{id}", [LineaController::class, "getEventByLinea"]);
        Route::get("lineas/videos/{id}", [LineaController::class, "getVideoByLinea"]);

        Route::get("lineas/events/{line}/{event}", [LineaController::class, "getEventDetail"]);
        //
        //*******************************************************
        //************************* PRODUCTS ********************
        //*******************************************************
        //
        Route::get("products/linea/{slug}", [ProductController::class, "getProductByLinea"]);
        Route::get("products/{line}/{product}", [ProductController::class, "detail"]);
        // ------event product------
        Route::get("products/events/{line}/{product}", [ProductController::class, "getEventByProductLine"]);
        Route::get("products/events/{line}/{product}/{event}", [ProductController::class, "getEventByProductLineDetail"]);

        //-------event video------
        Route::get("products/videos/{line}/{product}", [ProductController::class, "getVideoByProductLine"]);
        //
        //***********************************************************
        //************************* END PRODUCTS ********************
        //***********************************************************
        //

        //-------news------
        Route::get("news", [NewController::class, "getNews"]);
        Route::get("news/{slug}", [NewController::class, "getNewsDetail"]);

        //events--
        Route::get("events", [NewController::class, "getEvents"]);
        Route::get("events/{slug}", [NewController::class, "getDetailEvent"]);
        
        Route::get("galleries", [NewController::class, "getGalleries"]);
        //******************************?:-----------------latest------------------  //
        Route::get("latest", [NewController::class, "getLatest"]);
        Route::get("banners", [NewController::class, "getBannerDefault"]);
        
        

        //
        Route::get("test/{line}", [LineaController::class, "resultTest"]);

    });

    Route::post("products", [ProductController::class, "store"]);

    // Route::post("posts", [PostController::class, "store"]);
    // Route::get("posts", [PostController::class, "list"]);

    Route::group(['middleware' => ['jwt.verify']], function () {
        Route::get("users/me", [AuthController::class, "getUser"]);

    });

});
