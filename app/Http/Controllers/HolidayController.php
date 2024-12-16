<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Exception;
use App\Http\Requests\HolidayRequest;
use App\Http\Resources\HolidayCollection;
use App\Http\Resources\HolidayResource;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class HolidayController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/holiday/list",
     *     tags={"Holiday"},
     *     summary="Holiday list.",
     *     operationId="listHoliday",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Holiday list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Holiday list fetched successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Show the message to the User after fetching the list.",
     *             @OA\Schema(
     *                 type="Integer",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Holiday.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Holiday list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="Integer", example=1),
     *                      @OA\Property(property="holiday_name", type="String", example="Holiday Name"),
     *                      @OA\Property(property="date_from", type="Date", example="Date From"),
     *                      @OA\Property(property="date_to", type="Date", example="Date To"),
     *                      @OA\Property(property="days", type="Integer", example="Days"),
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
     *         description="Not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not found"
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {

            $currentYear        = date('Y');
            $currentStartDate   = "{$currentYear}-01-01";
            $currentEndDate     = "{$currentYear}-12-31";

            $holidays = Holiday::where(function ($query) use ($currentStartDate, $currentEndDate) {
                $query->where('date_from', '>=', $currentStartDate)
                    ->where('date_from', '<=', $currentEndDate)
                    ->orWhere('date_to', '>=', $currentStartDate)
                    ->where('date_to', '<=', $currentEndDate);
            })
                ->where('status', 1)
                ->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Holiday list.',
                'data'      =>  new HolidayCollection($holidays) ?? []
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    # Show the form for creating a new resource.
    #
    # @return \Illuminate\Http\Response
    public function create()
    {
        //
    }

    /**
     * @OA\POST(
     *     path="/v1/holiday/create",
     *     tags={"Holiday"},
     *     summary="Create New Holiday.",
     *     operationId="createHoliday",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New Holiday.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"holiday_name", "date_from", "date_to", "days"},
     *                 @OA\Property(
     *                     property="holiday_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="date_from",
     *                     type="Date"
     *                 ),
     *                 @OA\Property(
     *                     property="date_to",
     *                     type="Date"
     *                 ),
     *                 @OA\Property(
     *                     property="days",
     *                     type="integer"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Holiday created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Holiday.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Holiday created successfully."),
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
     *         description="Not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not found"
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HolidayRequest $request)
    {
        try {

            Holiday::create([
                'holiday_name'      =>  trim($request->holiday_name) ?? "",
                'date_from'         =>  $request->date_from ?? "",
                'date_to'           =>  $request->date_to ?? "",
                'days'              =>  (int)$request->days ?? "",
                'status'            =>  1,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Holiday created successfully.'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\GET(
     *     path="/v1/holiday/show/{id}",
     *     tags={"Holiday"},
     *     summary="Find Holiday Details",
     *     operationId="showHoliday",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Holiday ID",
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
     *             description="Status indicating that the Holiday was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Holiday.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Holiday.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Holiday Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="holiday_name", type="string", example="Republic Day"),
     *                      @OA\Property(property="date_from", type="Date", example="2024-01-26"),
     *                      @OA\Property(property="date_to", type="Date", example="2024-01-26"),
     *                      @OA\Property(property="days", type="integer", example="1"),
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
     *         description="Page not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Page not found"
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $holiday = Holiday::findOrFail($id);

            return response()->json([
                'status'    => true,
                'message'   => 'Holiday details Found.',
                'data'      => new HolidayResource($holiday)
            ], 200);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    => false,
                'message'   => 'Holiday not found.',
            ], 400);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    # Show the form for editing the specified resource.
    #
    # @param  int  $id
    # @return \Illuminate\Http\Response
    public function edit($id)
    {
        //
    }

    /**
     * @OA\PUT(
     *     path="/v1/holiday/update/{id}",
     *     tags={"Holiday"},
     *     summary="Update Holiday Details.",
     *     operationId="updateHoliday",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Holiday ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update Holiday Details.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"holiday_name", "date_from", "date_to", "days"},
     *                 @OA\Property(
     *                     property="holiday_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="date_from",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="date_to",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="days",
     *                     type="integer"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Holiday updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Holiday.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Holiday updated successfully."),
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
     *         description="Page not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Page not found"
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
    public function update(HolidayRequest $request, $id)
    {
        try {

            $holiday = Holiday::findOrFail($id);


            $holiday->update([

                'days'              => (int)$request->days ?? "",
                'date_to'           => $request->date_to ?? "",
                'date_from'         => $request->date_from ?? "",
                'holiday_name'      => trim($request->holiday_name) ?? ""
            ]);

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Holiday updated successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Holiday not found.'
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
     * @OA\DELETE(
     *     path="/v1/holiday/delete/{id}",
     *     tags={"Holiday"},
     *     summary="Delete Holiday.",
     *     operationId="deleteHoliday",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Holiday ID",
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
     *             description="Status indicating that the Holiday was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Holiday.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Holiday deleted successfully."),
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
     *         description="Not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not found"
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $holiday = Holiday::findOrFail($id);

            $holiday->delete();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Holiday deleted successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Holiday not found.'
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'exception' =>  $e->getMessage(),
                'message'   =>  'Something went wrong.'
            ], 500);
        }
    }

    /**
     * @OA\POST(
     *     path="/v1/holiday/search",
     *     tags={"Holiday"},
     *     summary="Holiday search.",
     *     operationId="searchHoliday",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Update Holiday Details.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={},
     *                 @OA\Property(
     *                     property="list_type",
     *                     type="string"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All or Upcoming Holidays list.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Holiday list fetched successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Show the message to the User after fetching the Holiday list.",
     *             @OA\Schema(
     *                 type="Integer",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Holidays list.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Holiday list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="Integer", example=1),
     *                      @OA\Property(property="holiday_name", type="String", example="New Year"),
     *                      @OA\Property(property="date_from", type="Date", example="01-01-2024"),
     *                      @OA\Property(property="date_to", type="Date", example="01-01-2024"),
     *                      @OA\Property(property="days", type="Integer", example="1"),
     *                      @OA\Property(property="day", type="String", example="Monday"),
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
     *         description="Not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not found"
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function search(Request $request)
    {
        $perPage        = $request->per_page == "" ? 10 : $request->per_page;
        $currentPage    = $request->current_page == "" ? 1 : $request->current_page;
        $searchKey      = $request->search_key ?? "";
        $listType       = $request->list_type ?? "upcoming_this_year";
        $orderBy        = isset($request->order_by) && $request->order_by != "" ? $request->order_by : "date_from";
        $orderType      = isset($request->order_type) && $request->order_type != "" ? $request->order_type : "asc";


        try {
            $holidays = Holiday::whereNotNull('holiday_name');

            if (!empty($searchKey)) {
                $holidays->where(function ($query) use ($searchKey) {
                    $query->where('holiday_name', 'LIKE', '%' . $searchKey . '%')
                        ->orWhere('date_from', 'LIKE', '%' . $searchKey . '%')
                        ->orWhere('date_to', 'LIKE', '%' . $searchKey . '%');
                });
            }

            if ($listType == 'upcoming_this_year') {
                $currentYear = date('Y');
                $currentStartDate = "{$currentYear}-01-01";
                $currentEndDate = "{$currentYear}-12-31";

                $holidays->where(function ($query) use ($currentStartDate, $currentEndDate) {
                    $query->where('date_from', '>=', $currentStartDate)
                        ->where('date_from', '<=', $currentEndDate)
                        ->orWhere('date_to', '>=', $currentStartDate)
                        ->where('date_to', '<=', $currentEndDate);
                })->where('date_from', '>=', date('Y-m-d'));

                $holidays = $holidays->orderBy($orderBy, $orderType)
                                        ->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();
            }

            if ($listType == 'all_this_year') {
                $currentYear = date('Y');
                $currentStartDate = "{$currentYear}-01-01";
                $currentEndDate = "{$currentYear}-12-31";

                $holidays->where('date_from', '>=', $currentStartDate)
                        ->where('date_to', '<=', $currentEndDate);
                        
                $holidays = $holidays->orderBy($orderBy, $orderType)
                                        ->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();
            }

            if ($listType == 'all') {
                $currentYear = date('Y');
                $currentStartDate = "{$currentYear}-01-01";

                // $holidays = $holidays->where('date_from', '>=', $currentStartDate)->orderBy($orderBy, $orderType)
                $holidays = $holidays->orderBy($orderBy, $orderType)
                                        ->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();
            }

            return new HolidayCollection($holidays);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\POST(
     *     path="/v1/holiday/upload-csv",
     *     tags={"Holiday"},
     *     summary="Create New Holiday.",
     *     operationId="createHoliday",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New Holiday.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"csv_file"},
     *                 @OA\Property(
     *                     property="csv_file",
     *                     type="file"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Holiday created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Holiday.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Holiday created successfully."),
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

    public function csv_upload(Request $request)
    {
        try { 
            if ($request->hasFile('csv_file')) {
                $file = $request->file('csv_file');                
                $csvFile = fopen($file->getPathname(), "r");
                fgetcsv($csvFile);

                $insertedHolidays = [];
                $skippedHolidays = [];

                while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
                    $holidayName = $data[0];
                    $fromDate = date('Y-m-d', strtotime($data[1]));

                    if (!Holiday::where('holiday_name', $holidayName)->where('date_from', $fromDate)->exists()) {
                        Holiday::create([
                            'days'              => $data[3],
                            'status'            => 1,
                            'date_to'           => date('Y-m-d', strtotime($data[2])),
                            'date_from'         => $fromDate,
                            'holiday_name'      => $holidayName,
                        ]);

                        $insertedHolidays[] = ['name' => $holidayName, 'from_date' => $fromDate];
                    } else {
                        $skippedHolidays[] = ['name' => $holidayName, 'from_date' => $fromDate];
                    }
                }
                
                fclose($csvFile);
                
                return response()->json([
                    'status' => true,
                    'message' => 'CSV file uploaded and data inserted successfully.',
                    'inserted_holidays' => $insertedHolidays,
                    'skipped_holidays' => $skippedHolidays,
                ], 201);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'CSV file Not uploaded.',
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }
}
