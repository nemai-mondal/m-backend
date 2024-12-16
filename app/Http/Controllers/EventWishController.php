<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventWish;
use App\Http\Requests\EventWishRequest;
use App\Http\Resources\EventWishCollection;
use App\Http\Resources\EventWishResource;
use Exception;

class EventWishController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/event-wish/list",
     *     tags={"Event-Wish"},
     *     summary="Event-Wish list.",
     *     operationId="listEvent-Wish",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Event-Wish list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Event-Wish list fetched successfully.",
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
     *             description="Contains the object of Event-Wish.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Event-Wish list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="message", type="string", example="Happy Work Anniversary"),
     *                      @OA\Property(property="created_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
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
        try {

            $wishesh = EventWish::orderBy('created_at', 'desc')->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Wish list.',
                'data'      =>  new EventWishCollection($wishesh) ?? []
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
     * @OA\POST(
     *     path="/v1/event-wish/create",
     *     tags={"Event-Wish"},
     *     summary="Create New Event-Wish.",
     *     operationId="createEvent-Wish",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New Event-Wish.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"wish_to_id"},
     *                 @OA\Property(
     *                     property="wish_to_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Event-Wish created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Event-Wish.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Event-Wish created successfully."),
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
    public function store(EventWishRequest $request)
    {
        try {
            
            $eventwish = EventWish::create([
                'wish_from_id'              =>  auth()->user()->id,
                'wish_to_id'                =>  (int)$request->wish_to_id,
                'message'                   =>  $request->message,
            ]);
            
            if ($eventwish) {

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Wish sent successfully.'
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

    public function show($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function myWishesh(Request $request)
    {
        $user_id = $request->user_id;

        try {
            
            $wishes = EventWish::where('wish_to_id', $user_id)
                                ->orderBy('id', 'desc')
                                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Event wishes retrieved successfully.',
                'data' => new EventWishCollection($wishes),
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
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

    /**
     * @OA\GET(
     *     path="/v1/event-wish/search",
     *     tags={"Event-Wish"},
     *     summary="Find Event-Wish Details",
     *     operationId="search Event-Wish",
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
     *             description="Status indicating that the Event-Wish found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the Event-Wish.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Event-Wish.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Event-Wish Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Happy Birthday"),
     *                      @OA\Property(property="created_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
     *                      @OA\Property(property="updated_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
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

            $wishesh = EventWish::select('id', 'wish_from_id', 'wish_to_id', 'message', 'created_at', 'updated_at')
                                    ->with('wishFrom', 'wishTo')
                                    ->orderBy('created_at', 'desc')
                                    ->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Wishesh list.',
                'data'      =>  $wishesh ?? [],
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
