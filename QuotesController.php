<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Helpers\Common;
use App\Quote;

class QuotesController extends Controller
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
        $quotes = Quote::whereStatus('1')->orderBy('id','desc')->get();
        return view('admin.pages.quotes',compact('quotes'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->category;
        $rules = array(
            'category'      => 'required',
            'name'          => 'required',
            'email'         => 'required|email:rfc,dns',
            'phone'         => 'required',
            'contactMode'   => 'required',
            'description'   => 'required',
        );

        $fieldNames = array(
            'category'      => 'Select Category',
            'name'          => 'Enter Your Name',
            'email'         => 'Enter a valid email address',
            'phone'         => 'Enter a phone number for our engineer to reach you',
            'contactMode'   => 'Select a contact mode',
            'description'   => 'Enter Project Description',
        );

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($fieldNames);

        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }
        else{

            $quote               = new Quote();
            $quote->name         = $request->name;
            $quote->category_id  = $request->category;
            $quote->email         = $request->email;
            $quote->phone         = $request->phone;
            $quote->contactMode   = $request->contactMode;
            $quote->address       = $request->address;
            $quote->description  = $request->description;
            $quote->save();

            $this->helper->one_time_message('success', __('Quote Request Sent Successfully!'));
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quote = Quote::whereId($id)->first();
        $quote->isRead = 1;
        $quote->save();
        // return $quote;
        return view('admin.pages.quoteShow',compact('quote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($quote)
    {
        // return $quote;
        $quote = Quote::whereId($quote)->first();
        $quote->delete();
        $this->helper->one_time_message('success', __('Quote Deleted Successfully!'));
        return redirect()->route('quotes.index');
    }
}
