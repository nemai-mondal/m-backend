<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotivationalQuoteRequest;
use App\Http\Resources\MotivationalQuoteCollection;
use App\Http\Resources\MotivationalQuoteResource;
use App\Models\MotivationalQuote;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MotivationalQuoteController extends Controller
{

    /**
     * @OA\GET(
     *     path="/v1/motivational-quote/list",
     *     tags={"Motivational Quote"},
     *     summary="Quote list.",
     *     operationId="listQuote",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Quote list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Quote list fetched successfully.",
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
     *             description="Contains the object of Quote.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Quote list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="quote_by", type="string", example="James Clear."),
     *                      @OA\Property(property="profile", type="string", example="12476576465.jpg"),
     *                      @OA\Property(property="quote", type="string", example="Always act with smartness."),
     *                      @OA\Property(property="created_by_id", type="integer", example=3),
     *                      @OA\Property(property="created_by_name", type="string", example="User Name"),
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

            $quotes = MotivationalQuote::orderBy('id', 'desc')->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Motivation Quotes fetched.',
                'data'      =>  new MotivationalQuoteCollection($quotes) ?? []
            ], 200);
        } catch (Exception $e) {

            return response()->json([
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
     *     path="/v1/motivational-quote/create",
     *     tags={"Motivational Quote"},
     *     summary="Create New Quote.",
     *     operationId="createQuote",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New Quote.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"quote"},
     *                 @OA\Property(
     *                     property="said_by",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="quote",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="display_date",
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
     *             description="Status indicates that Quote created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Quote.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Motivational Quote created successfully."),
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
    public function store(MotivationalQuoteRequest $request)
    {
        $quote              =   $request->quote ?? "";
        $said_by            =   $request->said_by ?? "";
        $display_date       =   $request->display_date == "" ? Carbon::now() : $request->display_date;

        try {

            $quote = MotivationalQuote::create([
                'quote'             =>  $quote,
                'said_by'           =>  $said_by,
                'user_id'           =>  auth()->user()->id,
                'display_date'      =>  $display_date,
                'status'            =>  1,
            ]);

            // If a new image is provided, add it to the 'author-avatar' media collection
            if ($request->hasFile("image")) {
                // If a new image is provided, clear the existing media in 'author-avatar' collection
                $quote->clearMediaCollection('author-avatar');

                $quote->addMediaFromRequest('image')
                    ->sanitizingFileName(function ($fileName) {
                        // Sanitize the filename by replacing special characters with dashes
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('author-avatar');
            }



            if ($quote) {

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Motivational Quote added successfully.',
                ], 201);
            }

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.'
            ], 400);
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
     *     path="/v1/motivational-quote/show/{id}",
     *     tags={"Motivational Quote"},
     *     summary="Find Quote Details",
     *     operationId="showQuote",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Quote ID",
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
     *             description="Status indicating that the Quote was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Quote.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Quote.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Quote Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="quote_by", type="string", example="James Clear."),
     *                      @OA\Property(property="profile", type="string", example="12476576465.jpg"),
     *                      @OA\Property(property="name", type="string", example="Quote Name"),
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

            $quote = MotivationalQuote::findOrFail($id);

            if (isset($quote) && $quote != null) {

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Motivation Quotes fetched.',
                    'data'      =>  new MotivationalQuoteResource($quote) ?? []
                ], 200);
            }
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Motivational Quote not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
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
     *     path="/v1/motivational-quote/update/{id}",
     *     tags={"Motivational Quote"},
     *     summary="Update Quote Details.",
     *     operationId="updateQuote",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Quote ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update Quote Details.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"quote"},
     *                 @OA\Property(
     *                     property="said_by",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="quote",
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
     *             description="Status indicates that Quote updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Quote.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Quote updated successfully."),
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MotivationalQuoteRequest $request, $id)
    {
        try {
            // Find the MotivationalQuote by its ID
            $quote = MotivationalQuote::findOrFail($id);

            // Update the MotivationalQuote with the specified fields (said_by and quote)
            $quote->update($request->only('said_by', 'quote'));

            // If a new image is provided, add it to the 'author-avatar' media collection
            if ($request->hasFile("image")) {
                // If a new image is provided, clear the existing media in 'author-avatar' collection
                $quote->clearMediaCollection('author-avatar');

                $quote->addMediaFromRequest('image')
                    ->sanitizingFileName(function ($fileName) {
                        // Sanitize the filename by replacing special characters with dashes
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('author-avatar');

                    $quote->touch();
            }

            // Return a JSON response indicating a successful update
            return response()->json([
                'status'  => true,
                'message' => 'Motivational Quote Updated Successfully.',
                'i' =>  1
            ], 201);
        } catch (ModelNotFoundException $e) {
            // Handle the case where the MotivationalQuote with the specified ID is not found
            return response()->json([
                'status'  => false,
                'message' => 'Motivational Quote not found.',
            ], 404);
        } catch (Exception $e) {
            // Handle other exceptions or errors
            return response()->json([
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * @OA\DELETE(
     *     path="/v1/motivation-quote/delete/{id}",
     *     tags={"Motivational Quote"},
     *     summary="Delete Quote.",
     *     operationId="deleteQuote",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Quote ID",
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
     *             description="Status indicating that the Quote was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Quote.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Quote deleted successfully."),
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $quote = MotivationalQuote::findOrFail($id);

            $quote->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Motivational Quote deleted successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Motivational Quote not found.',
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
     *     path="/v1/motivational-quote/search",
     *     tags={"Motivational Quote"},
     *     summary="Quote Search.",
     *     operationId="searchQuote",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Quote list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Quote list fetched successfully.",
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
     *             description="Contains the object of Quote.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Quote list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="quote_by", type="string", example="James Clear."),
     *                      @OA\Property(property="profile", type="string", example="12476576465.jpg"),
     *                      @OA\Property(property="quote", type="string", example="Always act with smartness."),
     *                      @OA\Property(property="created_by_id", type="integer", example=3),
     *                      @OA\Property(property="created_by_name", type="string", example="User Name"),
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
        try {
            $display_date = $request->input("display_date", null);
            $perPage = $request->input("per_page", 10);
            $currentPage = $request->input("current_page", 1);
            $searchKey = $request->search_key ?? "";
            $orderBy = isset($request->order_by) && $request->order_by != "" ? $request->order_by : "id";
            $orderType = isset($request->order_type) && $request->order_type != "" ? $request->order_type : "desc";

            $motivations = MotivationalQuote::whereNotNull("quote");

            if (!empty($searchKey)) {
                $motivations->where(function ($query) use ($searchKey) {
                    $query->whereRaw('LOWER(quote) LIKE ?', ['%' . strtolower(trim($searchKey)) . '%'])
                        ->orWhereRaw('LOWER(said_by) LIKE ?', ['%' . strtolower(trim($searchKey)) . '%']);
                });
            }

            if ($display_date != null) {
                $motivations->where('display_date', $display_date);
            }

            $motivations = $motivations->orderBy($orderBy, $orderType)
                ->paginate($perPage, ['*'], 'page', $currentPage)
                ->withQueryString();

            return new MotivationalQuoteCollection($motivations);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => "Something went wrong.",
                'exception' => $e->getMessage(),
            ], 500);
        }
    }
}
