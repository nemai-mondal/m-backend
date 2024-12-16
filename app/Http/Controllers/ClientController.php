<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientCollection;
use App\Http\Resources\ClientResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/client/list",
     *     tags={"Client"},
     *     summary="Client list.",
     *     operationId="listClient",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Client list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Client list fetched successfully.",
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
     *             description="Contains the object of Client.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Client list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Lucy Kye"),
     *                      @OA\Property(property="type", type="string", example="New"),
     *                      @OA\Property(property="site", type="string", example="International"),
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
        try {

            $clients = Client::with('projects')->orderBy('created_at', 'desc')->get();

            return new ClientCollection($clients);

            // return ClientResource::collection($clients);

            // return response()->json([
            //     'status'    =>  true,
            //     'message'   =>  'Clients list.',
            //     'data'      =>  new ClientCollection($clients) ?? []
            // ], 200);

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
     *     path="/v1/client/create",
     *     tags={"Client"},
     *     summary="Create New Client.",
     *     operationId="createClient",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New Client.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"name", "type", "site"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="site",
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
     *             description="Status indicates that Client created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Client.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Client created successfully."),
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
    public function store(ClientRequest $request)
    {

        try {
    
            $client = Client::create([
                'name'                          =>  trim($request->name) ?? "",
                'type'                          =>  $request->type ?? null,
                'site'                          =>  $request->site ?? null,
                'email'                         =>  $request->email ?? null,
                'phone'                         =>  $request->phone ?? null,
                'status'                        =>  $request->status ?? null,
                'country'                       =>  $request->country ?? null,
                'industry'                      =>  $request->industry ?? null,
                'company_name'                  =>  $request->company_name ?? null,
                'contact_person'                =>  $request->contact_person ?? null,
                'company_address'               =>  $request->company_address ?? null,
                'opportunity_source'            =>  $request->opportunity_source ?? null,
            ]); 
    
            if ($client) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Client created successfully.'
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
     * @OA\GET(
     *     path="/v1/client/show/{id}",
     *     tags={"Client"},
     *     summary="Find Client Details",
     *     operationId="showClient",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Client ID",
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
     *             description="Status indicating that the Client found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the Client.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Client.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Client Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Lucy Kye"),
     *                      @OA\Property(property="type", type="string", example="New"),
     *                      @OA\Property(property="site", type="string", example="International"),
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
     *         description="Not found",
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

            $client = Client::with('projects')->findOrFail($id);

            return response()->json([
                'status'    => true,
                'message'   => 'Client details found.',
                'data'      =>  new ClientResource($client),
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found.'
            ], 404);
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
     *     path="/v1/client/update/{id}",
     *     tags={"Client"},
     *     summary="Update Client Details.",
     *     operationId="updateClient",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Client ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update Client Details.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"name", "type", "site"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="site",
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
     *             description="Status indicates that Client updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Client.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Client updated successfully."),
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
    public function update(ClientRequest $request, $id)
    {
        try {

            $client = Client::findOrFail($id);
    
    
            $client->update([
                'name'                          =>  trim($request->name) ?? "",
                'type'                          =>  $request->type ?? null,
                'site'                          =>  $request->site ?? null,
                'email'                         =>  $request->email ?? null,
                'phone'                         =>  $request->phone ?? null,
                'status'                        =>  $request->status ?? null,
                'country'                       =>  $request->country ?? null,
                'industry'                      =>  $request->industry ?? null,
                'company_name'                 =>  $request->company_name ?? null,
                'contact_person'                =>  $request->contact_person ?? null,
                'company_address'              =>  $request->company_address ?? null,
                'opportunity_source'            =>  $request->opportunity_source ?? null,
            ]);


            return response()->json([
                'status'    =>  true,
                'message'   =>  'Client details updated successfully.'
            ], 201);

        } catch(ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Client not found.'
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
     *     path="/v1/client/delete/{id}",
     *     tags={"Client"},
     *     summary="Delete Client.",
     *     operationId="deleteClient",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Client ID",
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
     *             description="Status indicating that the Client was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Client.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Client deleted successfully."),
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

            $client = Client::findOrFail($id);


            $client->delete();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Client deleted successfully.'
            ], 200);
        } catch(ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Client not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.'
            ], 500);
        }
    }

    /**
     * @OA\GET(
     *     path="/v1/client/search",
     *     tags={"Client"},
     *     summary="Find Client Details",
     *     operationId="searchClient",
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
     *             description="Status indicating that the Client found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the Client.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Client.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Client Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Google"),
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
        $searchKey      = $request->search_key ?? "";
        $orderBy        = isset($request->order_by) && $request->order_by != "" ? $request->order_by : "id";
        $orderType      = isset($request->order_type) && $request->order_type != "" ? $request->order_type : "desc";

        try {

            $clients = Client::with('projects')->select('id', 'name', 'type', 'site', 'contact_person', 'email', 'phone', 'company_name', 'company_address', 'opportunity_source', 'industry', 'country', 'status', 'created_at', 'updated_at')
            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower(trim($searchKey)) . '%'])
            ->orderBy($orderBy, $orderType)
            ->paginate($perPage, ['*'], 'page', $currentPage)
            ->withQueryString();

            return response()->json([
                'status' => true,
                'message' => 'Client retrieved successfully.',
                'data' => $clients ?? [],
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