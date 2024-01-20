<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Category;
use App\File;
use Auth;
use Validator;
use App\Http\Helpers\Common;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    protected $helper;
    public function __construct()
    {
        $this->helper = new Common();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return view('admin.pages.productList',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::whereStatus('2')->get();
        return view('admin.pages.productAdd', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return "this";
         $rules = array(
            'category'      => 'required',
            'name'          => 'required',
            'price'         => 'required',
            'description'   => 'required',
            'file'          => 'mimes:png,jpg,jpeg,gif|max:10000',
        );

        $fieldNames = array(
            'category'      => 'Select Category',
            'name'          => 'Enter Product Name',
            'price'         => 'Add a price for the product',
            'description'   => 'Enter Product Description',
            'file'          => 'file must be an image (png, jpg, jpeg, gif)',
        );

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($fieldNames);

        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }
        else
        {
            $file = $request->file('file');
            if (isset($file))
            {

                $ext = $file->getClientOriginalExtension();
                //dd($ext);
                $categoryName = Category::whereId($request->category)->first();
                // return $categoryName->name;

                if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')
                {
                    $fileName        = Str::slug($request->name) . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/images/products');
                    
                    // return $destinationPath;
                    $file->move($destinationPath, $fileName);

                    // return $fileName;
                }
                else
                {
                    $this->helper->one_time_message('error', 'Invalid Image Format!');
                }
            }
            $product                 = new Product();
            $product->category_id    = $request->category;
            $product->name           = $request->name;
            $product->slug           = Str::slug($request->name);
            $product->price          = $request->price;
            $product->description    = $request->description;
            $product->picture        = isset($fileName) ? $fileName : null;
            $product->status         = $request->status;
            $product->save();

            $this->helper->one_time_message('success', __('Product Added Successfully!'));
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product = Product::whereId($product->id)->first();
        return view('admin.pages.productShow', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product = Product::whereId($product->id)->first();
        $categories = Category::whereStatus('1')->get();
        return view('admin.pages.productEdit', compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        {
            $this->validate($request, [
                'description' => 'required',
                'category'    => 'required',
                'name'        => 'required',
                'price'       => 'required',
                'file'        => 'mimes:png,jpg,jpeg,gif|max:10000',
            ]);

            $project                    = Product::whereId($product)->first();
            $product->category_id       = $request->category;
            $product->name              = $request->name;
            $product->slug              = Str::slug($request->name);
            $product->price             = $request->price;
            $product->description       = $request->description;
            $product->status            = $request->status;
            $product->save();

            // Store in Files Table
            $file = $request->file('file');
            if ($request->hasFile('file'))
            {
                $file_extn    = strtolower($file->getClientOriginalExtension());

                if ($file_extn == 'png'|| $file_extn == 'jpg' || $file_extn == 'jpeg' || $file_extn == 'gif')
                {
                    $fileName        = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/images/products');
                    $file->move($destinationPath, $fileName);

                    $product              = Product::whereId($request->product_id)->first();
                    $product->picture     = $fileName;
                    $product->save();
                }
                else
                {
                    $this->helper->one_time_message('error', 'Invalid File Format!');
                }
            }
            $this->helper->one_time_message('success', __('Product Updated Successfully!'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product = Product::whereId($product->id)->first();
        $product->delete();
        $this->helper->one_time_message('success', __('Product Deleted Successfully!'));
        return back();
    }



    
}
