<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectCollection;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;

class HRTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $projects = Project::orderBy('created_at', 'desc')->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Project list.',
                'data'      =>  new ProjectCollection($projects) ?? []
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
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

        try {
    
            $project = Project::create([
                'name'              =>  trim($request->name) ?? "",
                'status'            =>  1,
                'user_id'           =>  auth()->user()->id,
                'priority'          =>  $request->priority ?? null,
                'end_date'          =>  $request->filled('end_date') ? $request->start_date : null,
                'client_id'         =>  $request->client_id ?? "",
                'experience'        =>  $request->experience ?? null,
                'start_date'        =>  $request->filled('start_date') ? $request->start_date : null,
                'manager_id'        =>  $request->manager_id ?? null,
                'description'       =>  $request->description ?? null,
                'salary_range'      =>  $request->salary_range ?? null,
                'project_type'      =>  $request->project_type ?? null,
                'notice_period'     =>  $request->notice_period ?? null,
                'project_status'    =>  $request->project_status ?? null,
                'no_of_openings'    =>  $request->no_of_openings ?? null,
                'department_id'     =>  $request->department_id ?? null,
            ]);
    
            if ($project) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Project created successfully.'
                ], 201);
            }
    
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.'
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
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
        //
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
    public function destroy($id)
    {
        //
    }
}
