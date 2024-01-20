<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\Project;
use App\Category;
use App\File;

class PageController extends Controller
{
    public function index()
    {
        $projects = Project::whereStatus('1')->limit(3)->get();
        return view('pages.home',compact('projects'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function md()
    {
        return view('pages.md');
    }

    public function award()
    {
        return view('pages.awards');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function gallery()
    {
        return view('pages.gallery');
    }

    public function services()
    {
        return view('pages.services');
    }

    public function team()
    {
        return view('pages.team');
    }

    public function partners()
    {
        return view('pages.partners');
    }

    public function requestQuote(Request $request)
    {
        $categories = Category::whereStatus('1')->get();
        return view('pages.quote',compact('categories'));
    }

    public function projects()
    {
        $projects = Project::whereStatus('1')->get();
        $categories = Category::whereStatus('1')->get();
        // return $projects;
        return view('pages.projects',compact('projects','categories'));
    }

    public function showProduct($slug)
    {
        $product = Product::whereSlug($slug)->whereStatus('1')->first();
        // return $project->file;
        $otherProducts = Product::where('id', '<>', $product->id)->whereStatus('1')->inRandomOrder()->limit(3)->get();
        // return $otherProducts;
        return view('pages.productShow',compact('product','otherProducts'));
    }

    public function showProject($slug)
    {
        $project = Project::whereSlug($slug)->whereStatus('1')->first();
        // return $project->file;
        $otherProjects = Project::where('id', '<>', $project->id)->whereStatus('1')->limit(3)->get();
        // return $otherProjects;
        return view('pages.projectShow',compact('project','otherProjects'));
    }

    public function homesystems()
    {
        $products = Product::whereCategoryId('11')->whereStatus('1')->get();
        return view('pages.homesystems', compact('products'));
    }

    public function lights()
    {
        $products = Product::whereCategoryId('10')->whereStatus('1')->get();
        return view('pages.lights', compact('products'));
    }

    public function panels()
    {
        $products = Product::whereCategoryId('8')->whereStatus('1')->get();
        return view('pages.panels', compact('products'));
    }

    public function inverters()
    {
        $products = Product::whereCategoryId('6')->whereStatus('1')->get();
        return view('pages.inverters', compact('products'));
    }

    public function batteries()
    {
        $products = Product::whereCategoryId('9')->whereStatus('1')->get();
        return view('pages.batteries', compact('products'));
    }

    public function chargeController()
    {
        $products = Product::whereCategoryId('7')->whereStatus('1')->get();
        return view('pages.chargeControllers', compact('products'));
    }



}
