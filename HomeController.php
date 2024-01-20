<?php

namespace App\Http\Controllers;
use App\Quote;
use App\Project;
use App\Product;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $projects   = Project::all()->count();
        $products   = Product::all()->count();
        $quotes     = Quote::all()->count();
        return view('admin.pages.dashboard', compact('projects', 'products','quotes'));
    }

    public function products()
    {
        // code...
    }
}
