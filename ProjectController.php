<?php

namespace App\Http\Controllers;

use App\Project;
use App\Category;
use App\File;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Support\Str;
use App\Http\Helpers\Common;

class ProjectController extends Controller
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
        $projects  = Project::all();
        return view('admin.pages.projectlist',compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::whereStatus('1')->get();
        return view('admin.pages.projectadd', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $rules = array(
            'category'      => 'required',
            'name'          => 'required',
            'date'          => 'required',
            'description'   => 'required',
            'file'          => 'mimes:png,jpg,jpeg,gif|max:10000',
        );

        $fieldNames = array(
            'category'      => 'Select Category',
            'name'          => 'Enter Project Name',
            'date'          => 'Select Date',
            'description'   => 'Enter Project Description',
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

                if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')
                {
                    $fileName        = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/uploads/files');
                    $file->move($destinationPath, $fileName);

                    // return $fileName;
                }
                else
                {
                    $this->helper->one_time_message('error', 'Invalid Image Format!');
                }
            }
            $project                 = new Project();
            $project->category_id    = $request->category;
            $project->name           = $request->name;
            $project->slug           = Str::slug($request->name);
            $project->date           = $request->date;
            $project->description    = $request->description;
            $project->picture        = isset($fileName) ? $fileName : null;
            $project->status         = $request->status;
            $project->save();

            $this->helper->one_time_message('success', __('Project Added Successfully!'));
            return redirect()->route('projects.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $project = Project::whereId($project->id)->first();
        $files = File::whereProjectId($project->id)->get();
        return view('admin.pages.projectShow', compact('project','files'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $project = Project::whereId($project->id)->first();
        // return $project->category;
        $files = File::whereProjectId($project->id)->get();
        $categories = Category::whereStatus('1')->get();
        return view('admin.pages.projectEdit', compact('project','categories','files'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project)
    {
        {
            $this->validate($request, [
                'description' => 'required',
                'category'    => 'required',
                'name'        => 'required',
                'date'        => 'required',
                'file'        => 'mimes:png,jpg,jpeg,gif|max:10000',
            ]);

            $project                    = Project::whereId($project)->first();
            $project->category_id       = $request->category;
            $project->name              = $request->name;
            $project->slug              = Str::slug($request->name);
            $project->date              = $request->date;
            $project->description       = $request->description;
            $project->status            = $request->status;
            $project->save();

            // Store in Files Table
            $file = $request->file('file');
            if ($request->hasFile('file'))
            {
                $file_extn    = strtolower($file->getClientOriginalExtension());

                 

                if ($file_extn == 'png'|| $file_extn == 'jpg' || $file_extn == 'jpeg' || $file_extn == 'gif')
                {
                    $fileName        = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/uploads/files');
                    $file->move($destinationPath, $fileName);

                    $project              = Project::whereId($request->project_id)->first();
                    $project->picture     = $fileName;
                    $project->save();
                }
                else
                {
                    $this->helper->one_time_message('error', 'Invalid File Format!');
                }
            }
            $this->helper->one_time_message('success', __('Project Updated Successfully!'));
            return redirect()->back();
        }
    }

    public function addPictures(Request $request, Project $project)
    {
        $rules = array(
            'file'  => 'mimes:png,jpg,jpeg,gif|max:10000',
        );

        $fieldNames = array(
            'file'  => 'file must be an image (png, jpg, jpeg, gif)',
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

                if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')
                {
                    $fileName        = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/uploads/files');
                    $file->move($destinationPath, $fileName);

                    // return $fileName;
                }
                else
                {
                    $this->helper->one_time_message('error', 'Invalid Image Format!');
                }
            }
            $file                = new File();
            $file->project_id    = $request->project_id;
            $file->file          = isset($fileName) ? $fileName : null;
            $file->status        = '1';
            $file->save();

            $this->helper->one_time_message('success', __('File Added Successfully!'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project = Project::whereId($project->id)->first();
        $project->delete();
        $this->helper->one_time_message('success', __('Project Deleted Successfully!'));
        return back();
    }



}
