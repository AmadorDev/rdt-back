<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Linea;
use App\Models\Product;
use App\Models\Salon;
;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
class DashboardController extends Controller
{
    public function index(Request $req)
    {
        return Inertia::render('Dashboard', [
           'category'=>Category::count(),
           'line'=>Linea::count(),
           'salon'=>Salon::count(),
           'product'=>Product::count(),
        ]);
    }
}
