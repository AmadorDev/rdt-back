<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LineaController;
use App\Http\Controllers\LineaEventController;
use App\Http\Controllers\LineaVideoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductEventController;
use App\Http\Controllers\ProductVideoController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\NoveltyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GaleryController;
use App\Http\Controllers\LatestReleaseController;
use App\Http\Controllers\BannerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
    return redirect()->route('login');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->name('dashboard');

Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::get("dashboard", [DashboardController::class, "index"])->name('dashboard');

    Route::get("categories", [CategoryController::class, "index"])->name('category');
    Route::get("categories/add", [CategoryController::class, "create"])->name('category.add');
    Route::get("categories/edit/{id}", [CategoryController::class, "edit"])->name('category.edit');
    Route::put("categories/update/{id}", [CategoryController::class, "update"])->name('category.update');
    Route::post("categories", [CategoryController::class, "store"]);
    Route::delete("categories/{id}", [CategoryController::class, "destroy"])->name("category.destroy");


    Route::get("lines", [LineaController::class, "index"])->name('line');
    Route::get("lines/add", [LineaController::class, "create"])->name('line.add');
    Route::get("lines/edit/{id}", [LineaController::class, "edit"])->name('line.edit');
    Route::post("lines", [LineaController::class, "store"])->name('line.store');
    Route::put("lines/update/{id}", [LineaController::class, "update"])->name('line.update');
    Route::delete("lines/destroy/{id}", [LineaController::class, "destroy"])->name('line.destroy');
    Route::get("lines/image/{id}", [LineaController::class, "image"])->name('line.image');
    Route::delete("lines/image/{id}", [LineaController::class, "destroyImage"])->name('line.image.destroy');
    Route::put("lines/image/{id}", [LineaController::class, "updateImageCover"])->name('line_image.cover');


    Route::get("lines/events", [LineaEventController::class, "index"])->name('event');
    Route::get("lines/events/create/{line}", [LineaEventController::class, "create"])->name('event.add');
    Route::post("lines/events", [LineaEventController::class, "store"])->name('event.store');
    Route::get("lines/events/edit/{id}", [LineaEventController::class, "edit"])->name('event.edit');
    Route::post("lines/events/update/{id}", [LineaEventController::class, "update"])->name('event.update');
    Route::delete("lines/events/destroy/{id}", [LineaEventController::class, "destroy"])->name('event.destroy');
    Route::get("lines/events/image/{lineEvent}", [LineaEventController::class, "ImageIndex"])->name('testing.image');
    Route::post("lines/events/image/{lineEvent}", [LineaEventController::class, "ImageStore"])->name('testing_image.store');
    Route::delete("lines/events/image/{imageId}", [LineaEventController::class, "ImageDestroy"])->name('testing_image.destroy');



      
    Route::get("lines/videos", [LineaVideoController::class, "index"])->name('video');
    Route::get("lines/videos/create/{line}", [LineaVideoController::class, "create"])->name('video.add');
    Route::post("lines/videos", [LineaVideoController::class, "store"])->name('video.store');
    Route::get("lines/videos/edit/{id}", [LineaVideoController::class, "edit"])->name('video.edit');
    Route::put("lines/videos/update/{line}", [LineaVideoController::class, "update"])->name('video.update');
    Route::delete("lines/videos/destroy/{line}", [LineaVideoController::class, "destroy"])->name('video.destroy');


    Route::get("products", [ProductController::class, "index"])->name('product');
    Route::get("products/create", [ProductController::class, "create"])->name('product.add');
    Route::post("products", [ProductController::class, "store"])->name('product.store');
    Route::get("products/edit/{product}", [ProductController::class, "edit"])->name('product.edit');
    Route::post("products/update/{product}", [ProductController::class, "update"])->name('product.update');
    Route::delete("products/destroy/{product}", [ProductController::class, "destroy"])->name('product.destroy');

    Route::get("products/image/{product}", [ProductController::class, "ImagesIndex"])->name('product.image');
    Route::post("products/image/{product}", [ProductController::class, "ImagesStore"])->name('product_image.store');
    Route::delete("products/image/{id}", [ProductController::class, "ImageDestroy"])->name('product_image.destroy');

    Route::get("products/events", [ProductEventController::class, "index"])->name('product_event');
    Route::get("products/events/create/{product}", [ProductEventController::class, "create"])->name('product_event.add');
    Route::get("products/events/edit/{productEvent}", [ProductEventController::class, "edit"])->name('product_event.edit');
    Route::post("products/events", [ProductEventController::class, "store"])->name('product_event.store');
    Route::post("products/events/update/{productEvent}", [ProductEventController::class, "update"])->name('product_event.update');
    Route::delete("products/events/{productEvent}", [ProductEventController::class, "destroy"])->name('product_event.destroy');

    Route::get("products/videos", [ProductVideoController::class, "index"])->name('product_video');
    Route::get("products/videos/create/{product}", [ProductVideoController::class, "create"])->name('product_video.add');
    Route::post("products/videos", [ProductVideoController::class, "store"])->name('product_video.store');
    Route::get("products/videos/edit/{productVideo}", [ProductVideoController::class, "edit"])->name('product_video.edit');
    Route::put("products/videos/update/{productVideo}", [ProductVideoController::class, "update"])->name('product_video.update');
    Route::delete("products/videos/destroy/{productVideo}", [ProductVideoController::class, "destroy"])->name('product_video.destroy');


    Route::get("salons", [SalonController::class, "index"])->name('salon');
    Route::get("salons/create", [SalonController::class, "create"])->name('salon.add');
    Route::post("salons", [SalonController::class, "store"])->name('salon.store');
    Route::get("salons/edit/{salon}", [SalonController::class, "edit"])->name('salon.edit');
    Route::put("salons/update/{salon}", [SalonController::class, "update"])->name('salon.update');
    Route::delete("salons/destroy/{salon}", [SalonController::class, "destroy"])->name('salon.destroy');


    Route::get("news", [NoveltyController::class, "index"])->name('new');
    Route::get("news/create", [NoveltyController::class, "create"])->name('new.add');
    Route::post("news", [NoveltyController::class, "store"])->name('new.store');
    Route::get("news/edit/{novelty}", [NoveltyController::class, "edit"])->name('new.edit');
    Route::put("news/update/{novelty}", [NoveltyController::class, "update"])->name('new.update');
    Route::delete("news/destroy/{novelty}", [NoveltyController::class, "destroy"])->name('new.destroy');
    Route::get("news/images/{novelty}", [NoveltyController::class, "images"])->name('new.image');
    Route::post("news/images/{novelty}", [NoveltyController::class, "setImages"])->name('new_image.store');
    Route::delete("news/images/destroy/{id}", [NoveltyController::class, "destroyImage"])->name('new_image.destroy');
    Route::put("news/images/statecover/{id}", [NoveltyController::class, "updateCover"])->name('new_image.cover');

    Route::get("news/events", [EventController::class, "index"])->name('new_event');
    Route::get("news/events/add", [EventController::class, "create"])->name('new_event.add');
    Route::post("news/events", [EventController::class, "store"])->name('new_event.store');
    Route::get("news/events/edit/{event}", [EventController::class, "edit"])->name('new_event.edit');
    Route::post("news/events/update/{event}", [EventController::class, "update"])->name('new_event.update');
    Route::delete("news/events/destroy/{event}", [EventController::class, "destroy"])->name('new_event.destroy');

    Route::get("news/galleries", [GaleryController::class, "index"])->name('new_galery');
    Route::post("news/galleries", [GaleryController::class, "store"])->name('new_galery.store');
    Route::delete("news/galleries/{galery}", [GaleryController::class, "destroy"])->name('new_galery.destroy');

    Route::get("news/latests", [LatestReleaseController::class, "index"])->name('new_latest');
    Route::post("news/latests", [LatestReleaseController::class, "store"])->name('new_latest.store');
    Route::delete("news/latests/{latest}", [LatestReleaseController::class, "destroy"])->name('new_latest.destroy');

    Route::get("banners", [BannerController::class, "index"])->name('banner');
    Route::post("banners", [BannerController::class, "store"])->name('banner.store');
    Route::post("banners/status/{banner}", [BannerController::class, "status"])->name('banner.status');
    Route::delete("banners/{banner}", [BannerController::class, "destroy"])->name('banner.destroy');
    
});
  