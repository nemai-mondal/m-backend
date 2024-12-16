<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRatioRequest;
use App\Http\Resources\LeaveRatioCollection;
use App\Http\Resources\LeaveRatioResource;
use App\Http\Resources\LeaveTypeResource;
use App\Models\LeaveRatio;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class LeaveRatioController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/leave-ratio/list",
     *     tags={"Leave Ratio"},
     *     summary="LeaveRatio list.",
     *     operationId="listLeaveRatio",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="LeaveRatio list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the LeaveRatio list fetched successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Show the message to the user after fetching the list.",
     *             @OA\Schema(
     *                 type="Integer",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of LeaveRatio.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="LeaveRatio list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="leave_credit", type="string", example="0.5"),
     *                      @OA\Property(property="leave_frequency", type="string", example="Monthly"),
     *                      @OA\Property(property="leave_type", type="string", example="Casual Leave"),
     *                      @OA\Property(property="abbreviation", type="string", example="CL"),
     *                      @OA\Property(property="employment_type", type="string", example="Confirmed"),
     *                      @OA\Property(property="leave_credited_yearly", type="string", example="6"),
     *                  ),
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthenticated"
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not Found"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Server Error"
     *         ),
     *     )
     * )
     * 
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leave_ratios = LeaveRatio::get();

        return response()->json([
            'status'    =>  true,
            'message'   =>  'Leave Ratio Found.',
            'data'      =>  new LeaveRatioCollection($leave_ratios)
        ], 200);
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
     * @OA\POST(
     *     path="/v1/leave-ratio/create",
     *     tags={"Leave Ratio"},
     *     summary="Create New LeaveRatio.",
     *     operationId="createLeaveRatio",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New LeaveRatio.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"employment_type_id", "leave_type_id", "leave_credit", "frequency"},
     *                 @OA\Property(
     *                     property="employment_type_id",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="leave_type_id",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="leave_credit",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="frequency",
     *                     type="String"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that LeaveRatio created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the LeaveRatio.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="LeaveRatio created successfully."),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthenticated"
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not Found"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Server Error"
     *         ),
     *     )
     * )
     * 
     * 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeaveRatioRequest $request)
    {

        $emp_type_id       =  (int)$request->employment_type_id;
        $leave_type_id     =  (int)$request->leave_type_id;
        $leave_credit      =  (float)$request->leave_credit;
        $frequency         =  $request->frequency;

        try {

            $leaveRationExist = LeaveRatio::where('employment_type_id', $emp_type_id)
                ->where('leave_type_id', $leave_type_id)
                // ->where('leave_credit', $leave_credit)
                // ->where('frequency', $frequency)
                ->first();

            if (isset($leaveRationExist) && $leaveRationExist != null) {

                return response()->json([
                    'status'        =>  false,
                    'message'       =>  "Leave Policy with same Leave Type & Employment Type already exist."
                ], 400);
            }

            $leaveRatio = LeaveRatio::create([
                'employment_type_id'    =>  (int)$request->employment_type_id,
                'leave_type_id'         =>  (int)$request->leave_type_id,
                'leave_credit'          =>  (float)$request->leave_credit,
                'frequency'             =>  $request->frequency,
                'status'                =>  1,
            ]);

            if ($leaveRatio) {

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Leave Ratio created successfully.'
                ], 201);
            }

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.'
            ], 500);

        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\GET(
     *     path="/v1/leave-ratio/show/{id}",
     *     tags={"Leave Ratio"},
     *     summary="Find LeaveRatio Details",
     *     operationId="showLeaveRatio",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="LeaveRatio ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicating that the LeaveRatio found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the LeaveRatio.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of LeaveRatio.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="LeaveRatio Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="leave_credit", type="string", example="0.5"),
     *                      @OA\Property(property="leave_frequency", type="string", example="Monthly"),
     *                      @OA\Property(property="leave_type", type="string", example="Casual Leave"),
     *                      @OA\Property(property="abbreviation", type="string", example="CL"),
     *                      @OA\Property(property="employment_type", type="string", example="Confirmed"),
     *                      @OA\Property(property="leave_credited_yearly", type="string", example="6"),
     *                  ),
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Bad Request"
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthenticated"
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not Found"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Server Error"
     *         ),
     *     )
     * )
     * 
     * 
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $leave_ratio = LeaveRatio::findOrFail($id);
    
            return response()->json([
                'status'    =>  true,
                'message'   =>  'Leave Ratio Found',
                'data'      =>  new LeaveRatioResource($leave_ratio)
            ], 200);
        } catch (ModelNotFoundException $e) {
            
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Leave Ratio not found.',
            ], 404);
        } catch (Exception $e) {
            
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
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
     * @OA\PUT(
     *     path="/v1/leave-ratio/update/{id}",
     *     tags={"Leave Ratio"},
     *     summary="Update LeaveRatio Details.",
     *     operationId="updateLeaveType",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="LeaveRatio ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Create New LeaveRatio.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"employment_type_id", "leave_type_id", "leave_credit", "frequency"},
     *                 @OA\Property(
     *                     property="employment_type_id",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="leave_type_id",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="leave_credit",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="frequency",
     *                     type="String"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that LeaveRatio updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the LeaveRatio.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="LeaveRatio updated successfully."),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthenticated"
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not Found"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Server Error"
     *         ),
     *     )
     * )
     * 
     * 
     * 
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeaveRatioRequest $request, $id)
    {

        try {

            $leave_ratio = LeaveRatio::findOrFail($id);
        
            $leave_ratio->update([
                'employment_type_id'    => (int)$request->employment_type_id,
                'leave_type_id'         => (int)$request->leave_type_id,
                'leave_credit'          => (float)$request->leave_credit,
                'frequency'             => $request->frequency,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'LeaveType updated successfully.',
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Leave Ratio not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => 'Failed to update LeaveType.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/v1/leave-ratio/delete/{id}",
     *     tags={"Leave Ratio"},
     *     summary="Delete LeaveRatio.",
     *     operationId="deleteLeaveRatio",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="LeaveRatio ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicating that the LeaveRatio was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the LeaveRatio.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="LeaveRatio deleted successfully."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Bad Request"
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthenticated"
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not Found"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Server Error"
     *         ),
     *     )
     * )
     * 
     * 
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $leave_ratio = LeaveRatio::findOrFail($id);
    
            $leave_ratio->delete();
    
            return response()->json([
                'status'  => true,
                'message' => 'LeaveRatio deleted successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Leave Ratio not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\GET(
     *     path="/v1/leave-ratio/search",
     *     tags={"Leave Ratio"},
     *     summary="Find Leave Ratio Details",
     *     operationId="searchLeave Ratio",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=true,
     *         @OA\Schema(type="integer", default=10),
     *     ),
     *     @OA\Parameter(
     *         name="current_page",
     *         in="query",
     *         description="Current page number",
     *         required=true,
     *         @OA\Schema(type="integer", default=1),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicating that the Leave Ratio found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the Leave Ratio.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Leave Ratio.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Leave Ratio Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="employment_type_id", type="integer", example=2),
     *                      @OA\Property(property="leave_type_id", type="integer", example=1),
     *                      @OA\Property(property="leave_credit", type="integer", example=1),
     *                      @OA\Property(property="frequency", type="string", example="monthly"),
     *                      @OA\Property(property="created_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
     *                      @OA\Property(property="updated_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
     *                      @OA\Property(property="employment_type", type="integer", example=1),
     *                      @OA\Property(property="leave_type", type="integer", example=1),
     *                  ),
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Bad Request"
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthenticated"
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not Found"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Server Error"
     *         ),
     *     )
     * )
     * 
     * 
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function search(Request $request)
    {

        $perPage        = $request->per_page == "" ? 10 : $request->per_page;
        $currentPage    = $request->current_page == "" ? 1 : $request->current_page;;

        try {

            $leave_ratios = LeaveRatio::select('id', 'employment_type_id', 'leave_type_id', 'leave_credit', 'frequency', 'created_at', 'updated_at')
                                    ->with('employmentType', 'leaveType')
                                    ->orderBy('created_at', 'desc')
                                    ->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Leave Ratio list.',
                'data'      =>  $leave_ratios ?? [],
            ], 200);
            
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    } 
}
