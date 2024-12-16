<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;

use App\Models\User;
use App\Models\EmpPan;
use App\Models\EmpSkill;
use App\Models\EmpAdhaar;
use App\Models\EmpAssets;
use App\Models\EmpFamily;
use App\Models\EmpJoining;
use App\Models\EmpPassport;
use App\Models\Designation;
use App\Models\EmpLanguage;
use App\Models\EmpVoterCard;
use App\Models\EmpAttendance;
use App\Models\EmpSeparation;
use App\Models\EmpDepartment;
use App\Models\EmpDesignation;
use App\Models\EmpDocument;
use App\Models\EmpAddress;
use App\Models\EmpPersonalDetail;
use App\Models\EmpMailingAddress;
use App\Models\EmpDrivingLicense;
use App\Models\EmpEmploymentType;
use App\Models\EmpProfessionalDetail;
use App\Models\EmpQualificationDetails;

use App\Mail\CreateUserMail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Carbon\Carbon;

use Datetime;
use Exception;

// use App\Models\Role;
use App\Models\EmpRole;
use App\Models\EmpShift;
use App\Models\EmpProject;
use App\Models\Department;
use App\Mail\YourMailClass;
use App\Models\EmployeeAddress;
use App\Models\EmpOrganization;
use Illuminate\Validation\Rule;
use App\Models\EmpParmanentAddress;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    /**
     * @OA\GET(
     *     path="/v1/user/list",
     *     tags={"User"},
     *     summary="Users list.",
     *     operationId="listUser",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Users list fetched",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the users list fetched successfully.",
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
     *             description="Contains the object of users.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Users list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="User Name"),
     *                      @OA\Property(property="email", type="string", example="user@magicminds.io"),
     *                  ),
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=2),
     *                      @OA\Property(property="name", type="string", example="Another User"),
     *                      @OA\Property(property="email", type="string", example="another.user@magicminds.io"),
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
        try {
            // $users = User::whereNot('id', auth()->user()->id)
            $users = User::where('status', 1)
                ->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Users list.',
                'data'      =>  new UserCollection($users)
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
     *     path="/v1/user/create",
     *     tags={"User"},
     *     summary="Create New User Account.",
     *     operationId="createUser",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New User Account.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"first_name", "last_name", "employee_id", "email", "role_id", "department_id", "designation_id"},
     *                 @OA\Property(
     *                     property="honorific",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="middle_name",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="employee_id",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="role_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="department_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="designation_id",
     *                     type="Integer"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that account created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the account.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User account created successfully."),
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {

        $user_id                = auth()->user()->id;
        $employee_id            = trim(strtoupper($request->employee_id)) ?? "";
        $honorific              = trim($request->honorific) ?? null;
        $first_name             = trim($request->first_name) ?? null;
        $middle_name            = trim($request->middle_name) ?? null;
        $last_name              = trim($request->last_name) ?? null;
        $date_of_birth          = $request->date_of_birth ?? null;
        $date_of_joining        = $request->date_of_joining ?? null;
        $gender                 = $request->gender ?? null;
        // $personal_email         = trim(strtolower($request->personal_email)) ?? null;
        $office_email           = trim(strtolower($request->office_email)) ?? null;
        $phone                  = trim($request->phone) ?? null;
        $contractType           = trim($request->contract_type) ?? null;
        $reporting_manager_id   = $request->reporting_manager_id ?? null;
        $shift_id               = $request->shift_id ?? null;
        $employment_type_id     = $request->employment_type_id ?? null;
        $designation_id         = $request->designation_id ?? null;
        $department_id          = $request->department_id ?? null;
        $onboard_confirmed      = (int)$request->onboard_confirmed ?? false;

        $password               = $this->generatePassword();


        try {
            $user = User::create([
                'employee_id'       =>  $employee_id,
                'honorific'         =>  $honorific,
                'first_name'        =>  $first_name,
                'middle_name'       =>  $middle_name,
                'last_name'         =>  $last_name,
                'date_of_birth'     =>  $date_of_birth,
                'gender'            =>  $gender,
                'status'            =>  1,
                'email'             =>  $office_email,
                'password'          =>  Hash::make($password),
                'password_updated'  =>  0,
                'onboard_confirmed' =>  $onboard_confirmed,
            ]);

            if ($user) {

                $user_id = $user->id;

                EmpPersonalDetail::create([
                    'phone'             =>  $phone,
                    'user_id'           =>  $user_id,
                    'date_of_birth'     =>  $date_of_birth,
                    'gender'            =>  $gender,
                ]);

                EmpProfessionalDetail::create([
                    'user_id'               =>  $user_id,
                    'reporting_manager_id'  =>  $reporting_manager_id,
                    'contract_type'         =>  $contractType,
                    'date_of_joining'       =>  $date_of_joining
                ]);

                EmpShift::create([
                    'user_id'           =>  $user_id,
                    'shift_id'          =>  $shift_id,
                ]);

                EmpJoining::create([
                    'user_id'           =>  $user_id,
                    'date_of_joining'   =>  $date_of_joining,
                    'office_email'      =>  $office_email,
                ]);

                EmpEmploymentType::create([
                    'user_id'   =>  $user_id,
                    'employment_type_id'    =>  $employment_type_id
                ]);

                EmpOrganization::create([
                    'user_id'               => $user_id,
                    'location'              => $location ?? null,
                    'department_id'         => $department_id ?? null,
                    'effective_date'        => $effective_date ?? null,
                    'designation_id'        => $designation_id ?? null,
                ]);

                EmpDepartment::create([
                    "user_id"           =>  $user_id,
                    "department_id"     =>  $department_id
                ]);

                EmpDesignation::create([
                    "user_id"           =>  $user_id,
                    "designation_id"    =>  $designation_id
                ]);


                $user   = User::find($user_id);
                $roles  = Role::where('name', 'employee')->pluck('name');

                $user->assignRole($roles);


                $admin_emails = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->pluck('email');

                if ($first_name == "" && $middle_name == "" && $last_name == "") {
                    $honorific = "Dear User";
                }

                $emailData = [
                    'user'      => [
                        'honorific'         =>  $honorific,
                        'last_name'         =>  $last_name,
                        'employee_id'       =>  $employee_id,
                        'password'          =>  $password,
                        'email'             =>  $office_email,
                    ],
                ];

                if (!in_array(auth()->user()->email, (array)$admin_emails)) {
                    $admin_emails[sizeof($admin_emails)] = auth()->user()->email;
                }

                // Send email if account created successfully.
                Mail::to($office_email)
                    ->cc($admin_emails)
                    ->send(new CreateUserMail($emailData));

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'User account created successfully.',
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
     *     path="/v1/user/show/{id}",
     *     tags={"User"},
     *     summary="Find User Account",
     *     operationId="showUser",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
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
     *             description="Status indicating that the account was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the account.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of users.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="User Name"),
     *                      @OA\Property(property="email", type="string", example="user@magicminds.io"),
     *                  ),
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=2),
     *                      @OA\Property(property="name", type="string", example="Another User"),
     *                      @OA\Property(property="email", type="string", example="another.user@magicminds.io"),
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

            $user = User::with(
                'projects',
                'employeeProfessionalDetail',
                'employeePersonalDetail',
                'employmentType',
                'designation',
                'department',
                'adhaar',
                'pan',
                'voterCard',
                'drivingLicense',
                'passport',
                // 'role',
                'shift',
                'joining',
                'attendance',
            )
                ->where("id", $id)
                ->where('status', 1)
                ->first();


            if (isset($user) && $user != null) {

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'User Details Found.',
                    // 'data'      =>  new UserCollection($users)
                    // 'data'      =>  ($user) ?? []
                    'data'      =>  new UserResource($user) ?? []
                ], 200);
            }

            return response()->json([
                'status'    =>  false,
                'message'   =>  'User not found.'
            ], 400);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
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
     *     path="/v1/user/update/{id}",
     *     tags={"User"},
     *     summary="Update User Account.",
     *     operationId="updateUser",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update User Account.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"name", "email", "role_id", "department_id"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="role_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="department_id",
     *                     type="Integer"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that account updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the account.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Account updated successfully."),
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

    public function update(UserRequest $request, $id)
    {

        $gender                 = $request->gender ?? "";
        $phone                  = $request->phone ?? "";
        $honorific              = trim($request->honorific) ?? "";
        $last_name              = trim($request->last_name) ?? "";
        $first_name             = trim($request->first_name) ?? "";
        $middle_name            = trim($request->middle_name) ?? "";
        $date_of_birth          = $request->date_of_birth == '' ? null : $request->date_of_birth;
        $employment_type_id     = $request->employment_type_id ?? "";
        $reporting_manager_id   = $request->reporting_manager_id ?? "";

        $user_id                = auth()->user()->id;

        try {

            $user = User::find($user_id);

            if (isset($user) && $user != null) {

                $user->honorific        = $honorific;
                $user->last_name        = $last_name;
                $user->first_name       = $first_name;
                $user->middle_name      = $middle_name;

                if ($user->save()) {

                    EmpPersonalDetail::where('user_id', $user_id)->update([
                        'phone'             =>  $phone,
                        'date_of_birth'     =>  $date_of_birth,
                        'gender'            =>  $gender,
                    ]);

                    if (isset($reporting_manager_id)) {
                        EmpProfessionalDetail::where('user_id', $user_id)->update([
                            'reporting_manager_id'  =>  $reporting_manager_id,
                        ]);
                    }

                    if (isset($employment_type_id)) {
                        EmpEmploymentType::updateOrInsert(
                            [
                                'user_id'               =>  $user_id,
                                'employment_type_id'    =>  $employment_type_id,
                            ],
                            [
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]
                        );
                    }

                    return response()->json([
                        'status'    =>  true,
                        'message'   =>  'Account updated successfully.'
                    ], 201);
                }
            }

            return response()->json([
                'status'    =>  false,
                'message'   =>  'User not found.'
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
     * @OA\DELETE(
     *     path="/v1/user/delete/{id}",
     *     tags={"User"},
     *     summary="Delete User Account",
     *     operationId="deleteUser",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
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
     *             description="Status indicating that the account was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the account.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User account deleted successfully."),
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
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'User account deleted successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'User not found.'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.'
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $name           = $request->name ?? "";
        $department_id  = $request->department_id ?? "";

        $order_by       = $request->order_by ?? "created_at";
        $order_type     = $request->order_type ?? "desc";

        $user_id        = $request->user_id ?? "";

        $perPage        = $request->per_page == "" ? 10 : $request->per_page;
        $currentPage    = $request->current_page == "" ? 1 : $request->current_page;;


        try {

            $users = User::with('designation', 'department', 'employeePersonalDetail', 'separation')
                ->where('status', 1);

            if ($name != "") {

                $user_ids   = User::select('id')
                    ->where('first_name', 'like', '%' . $name . '%')
                    ->orWhere('middle_name', 'like', '%' . $name . '%')
                    ->orWhere('last_name', 'like', '%' . $name . '%')
                    ->pluck('id')
                    ->toArray();

                $users = $users->where('id', $user_ids);
            }

            if ($department_id != "") {
                $user_ids = EmpDepartment::select('user_id')->where('department_id', $department_id)->get();
                $users = $users->whereIn('id', $user_ids);
            }

            if (isset($user_id) && $user_id != "") {
                $users = $users->where('id', $user_id);
            }

            $users = $users->orderBy($order_by, $order_type);

            $users = $users->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

            // return $users;
            return new UserCollection($users);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\POST(
     *     path="/v1/user/assign-role",
     *     tags={"User"},
     *     summary="Add Role to User.",
     *     operationId="roleUser",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Add Role to User.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"user_id", "role_ids"},
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="Integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="role_ids",
     *                     type="array",
     *                     @OA\Items(type="integer"),
     *                     example={1, 2, 3}
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Role assigned to the User successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after assigning the role.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role assigned to the User successfully."),
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignRole(Request $request)
    {
        $user_id    = $request->user_id ?? "";
        $role_ids   = $request->role_ids ?? "";

        if ($user_id == auth()->user()->id) {
            return response()->json([
                'status'  => false,
                'message' => 'You cannot update your Role.',
            ], 422);
        }

        $user   = User::find($user_id);
        $roles  = Role::whereIn('id', $role_ids)->pluck('name');

        $user->assignRole($roles);

        return response()->json([
            'status'    =>  true,
            'message'   => 'Role assigned to User successfully.'
        ]);
    }

    /**
     * @OA\POST(
     *     path="/v1/user/remove-role",
     *     tags={"User"},
     *     summary="Remove a Role from User.",
     *     operationId="roleUser",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Remove a Role from User.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"user_id", "role_id"},
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="Integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="role_id",
     *                     type="integer",
     *                     example=4
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Role removed from User successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after removing the role.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role removed from User successfully."),
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeRole(Request $request)
    {
        try {

            $user_id    = $request->user_id ?? "";
            $role_id    = $request->role_id ?? "";

            if ($user_id == auth()->user()->id) {
                return response()->json([
                    'status'  => false,
                    'message' => 'You cannot remove your Role.',
                ], 422);
            }

            $user       = User::find($user_id);
            $role       = Role::where('id', $role_id)->value('name');

            $user->removeRole($role);

            return response()->json([
                'status'    =>  true,
                'message'   => 'Role removed from User successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   => 'Invalid ID.',
                'exception' =>  $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   => 'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\POST(
     *     path="/v1/user/assign-permission",
     *     tags={"User"},
     *     summary="Assign Direct Permission.",
     *     operationId="assignPermission",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Assign Direct Permission.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"user_id", "permission_ids"},
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="permission_ids",
     *                     type="array",
     *                     @OA\Items(type="integer"),
     *                     example={1}
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Permission assigned successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after assigning the Permission to the User.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permission assigned successfully."),
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignPermission(Request $request)
    {
        $user_id        = $request->user_id ?? "";
        $permission_ids = $request->permission_ids ?? "";

        if ($user_id == auth()->user()->id) {
            return response()->json([
                'status'  => false,
                'message' => 'You cannot update your Permission.',
            ], 422);
        }

        $user           = User::find($user_id);
        $permissions    = Permission::whereIn('id', $permission_ids)->pluck('name');

        $user->givePermissionTo($permissions);

        return response()->json([
            'status'    =>  true,
            'message'   => 'Permissions assigned to User successfully.'
        ]);
    }

    /**
     * @OA\PATCH(
     *     path="/v1/user/update-password/{id}",
     *     tags={"User"},
     *     summary="Update Password",
     *     operationId="updatePassword",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update the account password.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"old_password", "password", "password_confirmation"},
     *                 @OA\Property(
     *                     property="old_password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
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
     *             description="Status indicates that account password updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the password successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password updated successfully."),
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
     */
    public function updatePassword($id, Request $request)
    {

        $rules = [
            'old_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])/',
                'regex:/^(?=.*[A-Z])/',
                'regex:/^(?=.*\d)/',
                'regex:/^(?=.*[@$!%*?&])/',
            ],
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])/',
                'regex:/^(?=.*[A-Z])/',
                'regex:/^(?=.*\d)/',
                'regex:/^(?=.*[@$!%*?&])/',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (Hash::check($value, auth()->user()->password)) {
                        $fail('New password can not be same as old password.');
                    }
                },
            ],
        ];

        $customMessages = [
            'required'              =>  'The :attribute field is required.',
            'password.regex'        =>  'Password must have at least one Uppercase, one Lowercase, one Number, and one Special Character.',
            'old_password.regex'    =>  'Password must have at least one Uppercase, one Lowercase, one Number, and one Special Character.',
        ];

        $this->validate($request, $rules, $customMessages);

        try {

            $user = User::where('id', $id)
                ->where('status', 1)
                ->first();

            if (isset($user) && $user != null) {

                if (Hash::check($request->old_password, auth()->user()->password)) {

                    $password_update    =   User::where('id', auth()->user()->id)->update([
                        'password'      =>  Hash::make(trim($request->password)),
                    ], 201);

                    if ($password_update) {

                        return response()->json([
                            'status'    =>  true,
                            'message'   =>  'Password updated successfully.'
                        ], 201);
                    }

                    return response()->json([
                        'status'    =>  false,
                        'message'   =>  'Something went wrong.'
                    ], 500);
                }

                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'Incorrect old password.'
                ], 401);
            }

            return response()->json([
                'status'    =>  false,
                'message'   =>  'User not found.'
            ], 401);
        } catch (Exception $e) {

            return response()->json([
                'status'        =>  false,
                'message'       =>  'Something went wrong.',
                'exceptioin'    =>  $e->getMessage(),
            ], 500);
        }
    }

    public function details()
    {

        try {

            // $user = User::with(
            //     'projects',
            //     'employeeProfessionalDetail',
            //     'employeePersonalDetail',
            //     'employmentType',
            //     'designation',
            //     'department',
            //     'adhaar',
            //     'pan',
            //     'voterCard',
            //     'drivingLicense',
            //     'passport',
            //     'role',
            //     'shift',
            // )->findOrFail(auth()->user()->id);

            $user = User::findOrFail(auth()->user()->id);

            return response()->json([
                'status'    =>  true,
                'message'   =>  'User Details Found.',
                'data'      =>  new UserResource($user) ?? []
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'User details not found.'
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function generatePassword()
    {
        $numberChars    = '0123456789';
        $specialChars   = '!@#$%^&*()-_+=';
        $uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';

        $password = '';

        $password .= $uppercaseChars[rand(0, 25)];
        $password .= $lowercaseChars[rand(0, 25)];
        $password .= $numberChars[rand(0, 9)];
        $password .= $specialChars[rand(0, 11)];

        // Add additional characters to meet the minimum length
        $remainingLength = max(0, 8 - strlen($password));
        $allChars = $uppercaseChars . $lowercaseChars . $numberChars . $specialChars;

        for ($i = 0; $i < $remainingLength; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }

        // Shuffle the password
        $passwordArray = str_split($password);
        shuffle($passwordArray);
        $password = implode('', $passwordArray);

        return $password;
    }

    public function getCelebrationsList()
    {
        try {

            $currentYear = Carbon::now()->year;

            $workAnniversary = EmpProfessionalDetail::select(
                'user_id',
                'date_of_joining'
            )
                ->whereRaw("DATE_FORMAT(date_of_joining, '%m%d') BETWEEN ? AND ?", [Carbon::now()->format('md'), Carbon::now()->addDays(15)->format('md')])
                ->whereYear('date_of_joining', '<', Carbon::now()->year)
                ->get()
                ->toArray();

            $birthAnniversary = EmpPersonalDetail::select(
                'user_id',
                'date_of_birth'
            )
                ->whereRaw("DATE_FORMAT(date_of_birth, '%m%d') BETWEEN ? AND ?", [Carbon::now()->format('md'), Carbon::now()->addDays(15)->format('md')])
                ->get()
                ->toArray();

            $result = array_merge($workAnniversary, $birthAnniversary);

            $groupedResult = [];
            foreach ($result as $item) {
                $date = $item['date_of_joining'] ?? $item['date_of_birth'];
                $monthDay = substr($date, 5);

                if (!isset($groupedResult[$monthDay])) {
                    $groupedResult[$monthDay] = [];
                }
                $item['event']              = isset($item['date_of_joining']) ? 'Work Anniversary' : 'Birthday';
                $user                       = User::select('id', 'honorific', 'first_name', 'middle_name', 'last_name', 'employee_id')->where('id', $item['user_id'])->first();
                $item['user']               = $user;
                $item['user']['image']      = $user->getMedia("profile-picture")->first()->original_url ?? "";
                $groupedResult[$monthDay][] = $item;
            }

            return response()->json([
                'status'        =>  true,
                'message'       =>  'Celebrating Events.',
                'data'          =>  $groupedResult
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function upcomingJoiningAnniversaries()
    {
        try {

            $startDate  =   Date('Y-m-d');
            $currentDate = new Datetime('now');
            $endDate = $currentDate->modify('+15 days')->format('Y-m-d');

            $users = User::with(['employeeProfessionalDetail', 'employeePersonalDetail'])
                ->whereHas('employeeProfessionalDetail', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date_of_joining', [$startDate, $endDate]);
                })
                ->orWhereHas('employeePersonalDetail', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date_of_birth', [$startDate, $endDate]);
                })
                ->get();

            return response()->json([
                'status'        =>  true,
                'message'       =>  'Upcoming celebrations.',
                'data'          =>  new UserCollection($users)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(UserRequest $request)
    {
        $step = $request->step ?? 0;

        switch ($step) {
            case 2:
                return $this->onboard($request);
                break;
            case 3:
                return $this->updateProfilepicture($request);
                break;
            case 4:
                return $this->updateHeaderDetails($request);
                break;
            case 5:
                return $this->updateAboutDetails($request);
                break;
            case 6:
                return $this->updateJoiningDetails($request);
                break;
            case 7:
                return $this->updateOrganizationDetails($request);
                break;
            case 8:
                return $this->updateAttendance($request);
                break;
            case 9:
                return $this->updateIdentity($request);
                break;
            case 10:
                return $this->updatePersonalDetails($request);
                break;
            case 11:
                return $this->updateOtherDetails($request);
                break;
            case 12:
                return $this->updateSeparationDetails($request);
                break;
            case 13:
                return $this->updateDocumentDetails($request);
            case 14:
                return $this->updateAssetDetails($request);
                break;
            default:
                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'Invalid or Missing Step Id.',
                ], 400);
        }
    }

    public function onboard(UserRequest $request)
    {

        $user_id                = $request->user_id ?? "";
        $user_id                = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;
        $reporting_manager_id   = $request->reporting_manager_id ?? "";
        $honorific              = $request->honorific ?? null;
        $first_name             = $request->first_name ?? null;
        $middle_name            = $request->middle_name ?? null;
        $last_name              = $request->last_name ?? null;
        $contractType           = $request->contractType ?? null;
        // $personal_email         = $request->personal_email ?? null;
        $office_email           = $request->office_email ?? null;
        $personal_phone         = $request->personal_phone ?? null;
        $gender                 = $request->gender ?? null;
        $date_of_birth          = $request->date_of_birth ?? null;
        $onboard_confirmed      = $request->onboard_confirmed ?? false;
        try {

            User::find($user_id)->update([
                'honorific'         =>  $honorific,
                'first_name'        =>  $first_name,
                'middle_name'       =>  $middle_name,
                'last_name'         =>  $last_name,
                // 'email'             =>  $office_email,
                'onboard_confirmed' =>  $onboard_confirmed,
            ]);

            // EmpProfessionalDetail::updateOrInsert(
            //     ['user_id' => $user_id],
            //     [
            //         'reporting_manager_id' => (int)$reporting_manager_id,
            //         'contract_type' => $contractType,
            //     ]
            // );

            EmpPersonalDetail::updateOrInsert(
                ['user_id' => $user_id],
                [
                    // 'email'          => $personal_email,
                    'phone'          => $personal_phone,
                    'gender'         => $gender,
                    'date_of_birth'  => $date_of_birth,
                ]
            );

            return response()->json([
                'status'  => true,
                'message' => 'Profile Updated successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfilepicture(UserRequest $request)
    {
        $user_id    = $request->user_id ?? "";
        $user_id    = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;
        $user       = User::findOrFail($user_id);

        if ($request->hasFile("image")) {
            $user = User::findOrFail($user_id);
            $user->clearMediaCollection('profile-picture');

            $user->addMediaFromRequest('image')
                ->sanitizingFileName(function ($fileName) {
                    return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                })
                ->toMediaCollection('profile-picture');
        }

        if ($user) {

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Profile Picture Updated Succesfully.',
            ], 201);
        }

        return response()->json([
            'status'    =>  false,
            'message'   =>  'Something went wrong.'
        ], 400);
    }

    public function updateHeaderDetails(UserRequest $request)
    {
        $user_id                = $request->user_id ?? "";
        $user_id                = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;
        $email                  = $request->email ?? null;
        $machine_code           = $request->machine_code ?? null;
        // $designation_id         = $request->designation_id ?? null;
        $date_of_joining        = $request->date_of_joining ?? "";
        $reporting_manager_id   = $request->reporting_manager_id ?? null;
        $shift_id               = $request->shift_id ?? null;

        try {

            User::updateOrInsert(
                ['id' => $user_id],
                ['email'     =>  $email,]
            );

            EmpProfessionalDetail::updateOrInsert(
                ['user_id' => $user_id],
                [
                    'reporting_manager_id'  =>  (int)$reporting_manager_id,
                    'date_of_joining'       => $date_of_joining,
                ]
            );

            EmpAssets::updateOrInsert(
                ['user_id' => $user_id],
                [
                    'sr_no'    =>  $machine_code,
                ]
            );

            EmpJoining::updateOrCreate(
                [
                    'user_id' => $user_id
                ],
                [
                    'office_email'              => $email,
                    'date_of_joining'           => $date_of_joining,
                ]
            );

            EmpShift::updateOrCreate(
                ['user_id' => $user_id],
                [
                    'shift_id'      =>  $shift_id,
                ]
            );

            // EmpDepartment::updateOrInsert(
            //     ['user_id' => $user_id],
            //     ['department_id' => $department_id]
            // );

            // EmpDesignation::updateOrInsert(
            //     ['user_id' => $user_id],
            //     ['designation_id' => $designation_id] 
            // );

            return response()->json([
                'status'  => true,
                'message' => 'Profile Updated successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updateAboutDetails(UserRequest $request)
    {
        $user_id           = $request->user_id ?? "";
        $user_id           = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;
        $phone             = $request->phone ?? "";
        $gender            = $request->gender ?? "";
        $lastName          = $request->last_name ?? "";
        $honorific         = $request->honorific ?? "";
        $firstName         = $request->first_name ?? "";
        $middleName        = $request->middle_name ?? "";
        $dateOfBirth       = $request->date_of_birth ?? null;
        // $contractType      = $request->contract_type ?? "";

        try {
            User::find($user_id)->update([
                'honorific'  => $honorific,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name'  => $lastName,
            ]);

            EmpPersonalDetail::updateOrInsert(
                ['user_id' => $user_id],
                [
                    'gender'        => $gender,
                    'phone'         => $phone,
                    'date_of_birth' => $dateOfBirth,
                ]
            );

            // EmpProfessionalDetail::updateOrInsert(
            //     ['user_id' => $user_id],
            //     ['contract_type' => $contractType]
            // );

            return response()->json([
                'status'  => true,
                'message' => 'Profile About Details Updated successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updateOrganizationDetails(Request $request)
    {
        $user_id = $request->user_id ?? auth()->user()->id;
        $location = $request->location ?? "";
        $department_id = $request->department_id ?? "";
        $effective_date = $request->filled('effective_date') ? date('Y-m-d', strtotime($request->effective_date)) : null;
        $designation_id = $request->designation_id ?? "";

        try {
            $organizations = EmpOrganization::where('user_id', $user_id)->get();
            $departments   = EmpDepartment::where('user_id', $user_id)->get();
            $designations   = EmpDesignation::where('user_id', $user_id)->get();

            foreach($organizations as $organization) {
                $organization->delete();
            }

            $existingOrganization = EmpOrganization::withTrashed()
                                                    ->where('department_id', $department_id)
                                                    ->where('designation_id', $designation_id)
                                                    ->where('user_id', $user_id)
                                                    ->first();
            if($existingOrganization) {
                $existingOrganization->restore();
            } else {
                EmpOrganization::create([
                    'user_id' => $user_id,
                    'department_id' => $department_id,
                    'designation_id' => $designation_id,
                    'location' => $location,
                    'effective_date' => $effective_date,
                ]);
            }

            foreach($departments as $department) {
                $department->delete();
            }
            $existingDepartments = EmpDepartment::withTrashed()
                                                    ->where('department_id', $department_id)
                                                    ->where('user_id', $user_id)
                                                    ->first();
            if($existingDepartments) {
                $existingDepartments->restore();
            } else {
                
                if ($department_id != "") { 
                    EmpDepartment::create([
                        "user_id" => $user_id,
                        "department_id" => $department_id
                    ]);
                }
            }
            
            foreach($designations as $designation) {
                $designation->delete();
            }
            $existingDesignations = EmpDesignation::withTrashed()
                                                    ->where('designation_id', $designation_id)
                                                    ->where('user_id', $user_id)
                                                    ->first();
            if($existingDesignations) {
                $existingDesignations->restore();
            } else {

                if ($designation_id != "") {
                    EmpDesignation::create([
                        "user_id" => $user_id,
                        "designation_id" => $designation_id
                    ]);
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'Organization Information Updated successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfessionalDetails(UserRequest $request)
    {

        $user_id                =  $request->user_id;
        $date_of_joining        =  $request->date_of_joining ?? null;
        $approving_manager_id   =  $request->approving_manager_id ?? null;
        $reporting_manager_id   =  $request->reporting_manager_id ?? null;


        try {


            $employee_professional_details = EmpProfessionalDetail::where('user_id', $user_id)->update([
                'user_id'               =>  $user_id,
                'date_of_joining'       =>  $date_of_joining,
                'approving_manager_id'  =>  $approving_manager_id,
                'reporting_manager_id'  =>  $reporting_manager_id,
            ]);

            if ($employee_professional_details) {

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Professional details updated successfully.'
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

    public function changeStatus(ChangeStatusRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->update([
                'status' => $request->input('status', 0)
            ]);

            return response()->json([
                'status'    => true,
                'message'   => 'User status updated successfully.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updateJoiningDetails(UserRequest $request)
    {
        $status                         = (int)$request->status ?? "";
        $user_id                        = $request->user_id ?? "";
        $office_email                   = $request->office_email ?? "";
        $contractType                   = $request->contract_type ?? "";
        $transfer_date                  = $request->filled('transfer_date') ? date('Y-m-d', strtotime($request->transfer_date)) : null;
        $date_of_joining                = $request->filled('date_of_joining') ? date('Y-m-d', strtotime($request->date_of_joining)) : null;
        $last_working_date              = $request->filled('last_working_date') ? date('Y-m-d', strtotime($request->last_working_date)) : null;
        $confirmation_date              = $request->confirmation_date ?? "";
        $confirmation_date              = $request->filled('confirmation_date') ? date('Y-m-d', strtotime($request->confirmation_date)) : null;
        $salary_start_date              = $request->filled('salary_start_date') ? date('Y-m-d', strtotime($request->salary_start_date)) : null;
        $employment_type_id             = $request->employment_type_id ?? "";
        $notice_period_employee         = $request->filled('notice_period_employee') ? (int)$request->notice_period_employee : null;
        $notice_period_employer         = $request->filled('notice_period_employer') ? (int)$request->notice_period_employer : null;
        $probation_period_in_days       = $request->filled('probation_period_in_days') ? (int)$request->probation_period_in_days : null;

        $user_id                        = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;

        User::where('id', $user_id)->update([
            'status' => $status
        ]);

        EmpJoining::updateOrCreate(
            [
                'user_id' => $user_id
            ],
            [
                'office_email'              => $office_email,
                'transfer_date'             => $transfer_date,
                'date_of_joining'           => $date_of_joining,
                'salary_start_date'         => $salary_start_date,
                'confirmation_date'         => $confirmation_date,
                'last_working_date'         => $last_working_date,
                'notice_period_employer'    => $notice_period_employer,
                'notice_period_employee'    => $notice_period_employee,
                'probation_period_in_days'  => $probation_period_in_days,
            ]
        );

        EmpEmploymentType::updateOrCreate([
            'user_id'               =>  $user_id,
            'employment_type_id'    =>  $employment_type_id
        ]);

        EmpProfessionalDetail::updateOrInsert(
            ['user_id' => $user_id],
            ['contract_type' => $contractType]
        );

        return response()->json([
            'status'    =>  true,
            'message'   =>  'Joining Details Updated Successfully.'
        ]);
    }

    public function updateIdentity($request)
    {
        $form = $request->form ?? 0;

        switch ($form) {
            case 1:
                return $this->updateAdhaar($request);
                break;
            case 2:
                return $this->updatePan($request);
                break;
            case 3:
                return $this->updateVoterCard($request);
                break;
            case 4:
                return $this->updateDrivingLicense($request);
                break;
            case 5:
                return $this->updatePassport($request);
                break;
            default:
                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'Form id missing.',
                ], 400);
        }
    }

    public function updateAttendance($request)
    {
        try {
            $user_id = $request->user_id ?? auth()->user()->id;

            $request->merge(array_map(function ($value) {
                return $value === '' ? null : $value;
            }, $request->all()));

            EmpAttendance::updateOrCreate(
                [
                    'user_id' => $user_id,
                ],
                $request->only([
                    'department_id',
                    'punch_required',
                    'cc_not_allowed',
                    'overtime_default',
                    'overtime_weekoff',
                    'overtime_holiday',
                    'single_punch_required',
                    'weekoff_start_default',
                    'weekoff_start_approved',
                ])
            );

            return response()->json([
                'status' => true,
                'message' => 'Attendance details updated successfully.'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateAdhaar($request)
    {
        $id             = $request->id ?? "";
        $key            = $request->key ?? "";
        $name           = $request->name ?? "";
        $user_id        = $request->user_id ?? "";
        $adhaar_no      = $request->adhaar_no ?? "";
        $enrollment_no  = $request->enrollment_no ?? "";
        $lock           = $request->lock ?? null;
        $user_id        = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;

        try {
            if ($key == "delete") {
                $emp_adhaar = EmpAdhaar::findOrFail($id);
                $emp_adhaar->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Employee Adhaar deleted successfully.'
                ], 200);
            } else {
                $adhaar = EmpAdhaar::updateOrCreate(
                    ['user_id'  =>  $user_id],
                    [
                        'name'          =>  $name,
                        'adhaar_no'     =>  $adhaar_no,
                        'enrollment_no' =>  $enrollment_no,
                        'lock'          =>  $lock,
                    ]
                );

                if ($adhaar && $request->hasFile("file")) {

                    $adhaar->clearMediaCollection('identity-adhaar');

                    $adhaar->addMediaFromRequest('file')
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('identity-adhaar');
                }


                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Adhaar details updated successfully.'
                ], 201);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Employee Adhaar not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([ 
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    public function verifyAdhaarCard(Request $request, $id)
    {
        $lock = $request->lock ?? null;

        try {
            $emp_adhaar = EmpAdhaar::findOrFail($id);

            if ($lock !== null) { 
                $emp_adhaar->lock = $lock;
                $emp_adhaar->save();

                $responseMessage = $lock ? 'Adhaar card Approved.' : 'Adhaar card Rejected.';
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Lock value not provided.'
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => $responseMessage
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Employee Adhaar not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePan($request)
    {
        $id         = $request->id ?? "";
        $key        = $request->key ?? "";
        $name       = $request->name ?? "";
        $number     = $request->number ?? "";
        $user_id    = $request->user_id ?? "";

        $user_id        = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;

        try {
            if ($key == "delete") {
                $emp_pan = EmpPan::findOrFail($id);
                $emp_pan->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Employee PAN deleted successfully.'
                ], 200);
            } else {
                $pan = EmpPan::updateOrCreate(
                    ['user_id'  =>  $user_id],
                    [
                        'name'      =>  $name,
                        'number'    =>  $number,
                    ]
                );

                if ($pan && $request->hasFile("file")) {

                    $pan->clearMediaCollection('identity-pan');

                    $pan->addMediaFromRequest('file')
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('identity-pan');
                }


                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'PAN Card details updated successfully.'
                ], 201);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Pan Card not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    public function verifyPanCard(Request $request, $id)
    {
        $lock = $request->lock ?? null;

        try {
            $emp_pan = EmpPan::findOrFail($id);

            if ($lock !== null) {
                $emp_pan->lock = $lock;
                $emp_pan->save();

                $responseMessage = $lock ? 'Pan card Approved.' : 'Pan card Rejected.';
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Lock value not provided.'
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => $responseMessage
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Employee Pan not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updateVoterCard($request)
    {
        $id         = $request->id ?? "";
        $key        = $request->key ?? "";
        $name       = $request->name ?? "";
        $number     = $request->number ?? "";
        $user_id    = $request->user_id ?? "";

        $user_id        = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;

        try {
            if ($key == "delete") {
                $emp_voter = EmpVoterCard::findOrFail($id);
                $emp_voter->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Employee Voter deleted successfully.'
                ], 200);
            } else {
                $voter_card = EmpVoterCard::updateOrCreate(
                    ['user_id'  =>  $user_id],
                    [
                        'name'      =>  $name,
                        'number'    =>  $number,
                    ]
                );

                if ($voter_card && $request->hasFile("file")) {

                    $voter_card->clearMediaCollection('identity-voter-card');

                    $voter_card->addMediaFromRequest('file')
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('identity-voter-card');
                }


                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Election Card details updated successfully.'
                ], 201);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Election Card not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    public function verifyVoterCard(Request $request, $id)
    {
        $lock = $request->lock ?? null;

        try {
            $emp_voter = EmpVoterCard::findOrFail($id);

            if ($lock !== null) {
                $emp_voter->lock = $lock;
                $emp_voter->save();

                $responseMessage = $lock ? 'Voter card Approved.' : 'Voter card Rejected.';
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Lock value not provided.'
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => $responseMessage
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Employee Voter not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updateDrivingLicense($request)
    {
        $id             = $request->id ?? "";
        $key            = $request->key ?? "";
        $name           = $request->name ?? "";
        $number         = $request->number ?? "";
        $user_id        = $request->user_id ?? "";
        $expiry_date    = $request->expiry_date ?? "";

        $user_id        = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;

        try {
            if ($key == "delete") {
                $emp_driving_license = EmpDrivingLicense::findOrFail($id);
                $emp_driving_license->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Employee Driving License deleted successfully.'
                ], 200);
            } else {
                $driving_license = EmpDrivingLicense::updateOrCreate(
                    ['user_id'  =>  $user_id],
                    [
                        'name'          =>  $name,
                        'number'        =>  $number,
                        'expiry_date'   =>  $expiry_date,
                    ]
                );

                if ($driving_license && $request->hasFile("file")) {

                    $driving_license->clearMediaCollection('identity-driving-license');

                    $driving_license->addMediaFromRequest('file')
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('identity-driving-license');
                }


                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Driving License details updated successfully.'
                ], 201);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Driving License not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    public function verifyDrivingLicense(Request $request, $id)
    {
        $lock = $request->lock ?? null;

        try {
            $emp_driving_license = EmpDrivingLicense::findOrFail($id);

            if ($lock !== null) {
                $emp_driving_license->lock = $lock;
                $emp_driving_license->save();

                $responseMessage = $lock ? 'Driving License card Approved.' : 'Driving License card Rejected.';
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Lock value not provided.'
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => $responseMessage
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Employee Driving License not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePassport($request)
    {
        $id             = $request->id ?? "";
        $key            = $request->key ?? "";
        $name           = $request->name ?? "";
        $number         = $request->number ?? "";
        $user_id        = $request->user_id ?? "";
        $country        = $request->country ?? "";
        $issue_date     = $request->issue_date ?? "";
        $expiry_date    = $request->expiry_date ?? "";

        $user_id        = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;

        try {
            if ($key == "delete") {
                $emp_passport = EmpPassport::findOrFail($id);
                $emp_passport->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Employee Passport deleted successfully.'
                ], 200);
            } else {
                $passport = EmpPassport::updateOrCreate(
                    ['user_id'  =>  $user_id],
                    [
                        'name'          =>  $name,
                        'number'        =>  $number,
                        'country'       =>  $country,
                        'issue_date'    =>  $issue_date,
                        'expiry_date'   =>  $expiry_date,
                    ]
                );

                if ($passport && $request->hasFile("file")) {

                    $passport->clearMediaCollection('identity-passport');

                    $passport->addMediaFromRequest('file')
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('identity-passport');
                }


                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Passport details updated successfully.'
                ], 201);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Passport not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    public function verifyPassport(Request $request, $id)
    {
        $lock = $request->lock ?? null;

        try {
            $emp_passport = EmpPassport::findOrFail($id);

            if ($lock !== null) {
                $emp_passport->lock = $lock;
                $emp_passport->save();

                $responseMessage = $lock ? 'Passport Approved.' : 'Passport Rejected.';
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Lock value not provided.'
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => $responseMessage
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Employee Passport not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePersonalDetails(UserRequest $request)
    {
        $form = $request->form ?? 0;

        switch ($form) {
            case 1:
                return $this->updatePersonalInformation($request);
                break;
            case 2:
                return $this->updateAddressInformation($request);
                break;
            case 3:
                return $this->updateFamilyInformation($request);
                break;
            case 4:
                return $this->updateEmergencyAddress($request);
                break;
            case 5:
                return $this->updateQualificationDetails($request);
                break;
            default:
                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'Form id missing.',
                ], 400);
        }
    }

    public function updatePersonalInformation(Request $request)
    {
        $user_id                 = $request->user_id ?? "";
        $user_id                 = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;
        $hobbies                 = $request->hobbies ?? "";
        $religion                = $request->religion ?? null;
        $father_name             = $request->father_name ?? "";
        $mother_name             = $request->mother_name ?? "";
        $nationality             = $request->nationality ?? "";
        $spouse_name             = $request->spouse_name ?? "";
        $marriage_date           = $request->marriage_date ?? null;
        $state_of_birth          = $request->state_of_birth ?? "";
        $place_of_birth          = $request->place_of_birth ?? "";
        $personal_email          = $request->personal_email ?? "";
        $marital_status          = $request->marital_status ?? "";
        $country_of_birth        = $request->country_of_birth ?? "";
        $confirmation_date       = $request->confirmation_date ?? "";
        $identification_mark1    = $request->identification_mark1 ?? "";
        $identification_mark2    = $request->identification_mark2 ?? "";
        $physical_disabilities   = $request->physical_disabilities ?? "";

        if ($marriage_date !== null) {
            $marriage_date = date('Y-m-d', strtotime($marriage_date));
        }

        try {

            EmpPersonalDetail::updateOrInsert(
                ['user_id' => $user_id],
                [
                    'hobbies'               => $hobbies,
                    'religion'              => $religion,
                    'nationality'           => $nationality,
                    'father_name'           => $father_name,
                    'mother_name'           => $mother_name,
                    'spouse_name'           => $spouse_name,
                    'marriage_date'         => $marriage_date,
                    'marital_status'        => $marital_status,
                    'personal_email'        => $personal_email,
                    'state_of_birth'        => $state_of_birth,
                    'place_of_birth'        => $place_of_birth,
                    'country_of_birth'      => $country_of_birth,
                    'confirmation_date'     => $confirmation_date,
                    'identification_mark1'  => $identification_mark1,
                    'identification_mark2'  => $identification_mark2,
                    'physical_disabilities' => $physical_disabilities,
                ]
            );


            return response()->json([
                'status'  => true,
                'message' => 'Personal Information Updated successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updateAddressInformation(Request $request)
    {
        $user_id                        = $request->user_id;
        $user_id                        = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;
        $is_checked                     = $request->is_checked ?? false;
        $mailing_wef                    = $request->mailing_wef ?? null;
        $mailing_city                   = $request->mailing_city ?? "";
        $address_type1                  = $request->address_type ?? "mailing";
        $address_type2                  = $request->address_type ?? "permanent";
        $mailing_state                  = $request->mailing_state ?? "";
        $mailing_line1                  = $request->mailing_line1 ?? "";
        $mailing_line2                  = $request->mailing_line2 ?? "";
        $mailing_line3                  = $request->mailing_line3 ?? "";
        $mailing_phone1                 = $request->mailing_phone1 ?? "";
        $mailing_phone2                 = $request->mailing_phone2 ?? "";
        $mailing_country                = $request->mailing_country ?? "";
        $mailing_pincode                = $request->mailing_pincode ?? "";
        $mailing_city_type              = $request->mailing_city_type ?? "";
        $mailing_land_line1             = $request->mailing_land_line1 ?? null;
        $mailing_land_line2             = $request->mailing_land_line2 ?? null;
        $parmanent_wef                  = $request->parmanent_wef ?? null;
        $parmanent_city                 = $request->parmanent_city ?? "";
        $parmanent_state                = $request->parmanent_state ?? "";
        $parmanent_line1                = $request->parmanent_line1 ?? "";
        $parmanent_line2                = $request->parmanent_line2 ?? "";
        $parmanent_line3                = $request->parmanent_line3 ?? "";
        $parmanent_phone1               = $request->parmanent_phone1 ?? "";
        $parmanent_phone2               = $request->parmanent_phone2 ?? "";
        $parmanent_country              = $request->parmanent_country ?? "";
        $parmanent_pincode              = $request->parmanent_pincode ?? "";
        $parmanent_city_type            = $request->parmanent_city_type ?? "";
        $parmanent_land_line1           = $request->parmanent_land_line1 ?? null;
        $parmanent_land_line2           = $request->parmanent_land_line2 ?? null;
        $permanent_same_as_current      = $request->permanent_same_as_current ?? 0;



        try {
            EmpAddress::updateOrInsert(
                [
                    'user_id'       => $user_id,
                    'address_type'  => $address_type1
                ],
                [
                    'wef'               => $mailing_wef,
                    'city'             => $mailing_city,
                    'state'            => $mailing_state,
                    'line1'            => $mailing_line1,
                    'line2'            => $mailing_line2,
                    'line3'            => $mailing_line3,
                    'phone1'           => $mailing_phone1,
                    'phone2'           => $mailing_phone2,
                    'country'          => $mailing_country,
                    'pincode'          => $mailing_pincode,
                    'city_type'        => $mailing_city_type,
                    'landline1'        => $mailing_land_line1,
                    'landline2'        => $mailing_land_line2,
                    'address_type'     => $address_type1,
                    'permanent_same_as_current' =>  $permanent_same_as_current,
                ]
            );


            if ($is_checked) {
                EmpAddress::updateOrInsert(
                    ['user_id' => $user_id, 'address_type' => $address_type2],
                    [
                        'wef'              => $mailing_wef,
                        'city'             => $mailing_city,
                        'state'            => $mailing_state,
                        'line1'            => $mailing_line1,
                        'line2'            => $mailing_line2,
                        'line3'            => $mailing_line3,
                        'phone1'           => $mailing_phone1,
                        'phone2'           => $mailing_phone2,
                        'country'          => $mailing_country,
                        'pincode'          => $mailing_pincode,
                        'city_type'        => $mailing_city_type,
                        'landline1'        => $mailing_land_line1,
                        'landline2'        => $mailing_land_line2,
                        'permanent_same_as_current' =>  $permanent_same_as_current,
                    ]
                );
            } else {
                EmpAddress::updateOrInsert(
                    ['user_id' => $user_id, 'address_type' => $address_type2],
                    [
                        'wef'              => $parmanent_wef,
                        'city'             => $parmanent_city,
                        'state'            => $parmanent_state,
                        'line1'            => $parmanent_line1,
                        'line2'            => $parmanent_line2,
                        'line3'            => $parmanent_line3,
                        'phone1'           => $parmanent_phone1,
                        'phone2'           => $parmanent_phone2,
                        'country'          => $parmanent_country,
                        'pincode'          => $parmanent_pincode,
                        'city_type'        => $parmanent_city_type,
                        'landline1'        => $parmanent_land_line1,
                        'landline2'        => $parmanent_land_line2,
                        'permanent_same_as_current' =>  $permanent_same_as_current,
                    ]
                );
            }

            return response()->json([
                'status' => true,
                'message' => 'Employee Mailing And Permanent Addresses Updated successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function updateFamilyInformation(Request $request)
    {
        $id                     =   $request->id ?? "";
        $key                    =   $request->key ?? "create";
        $name                   =   $request->name ?? "";
        $title                  =   $request->title ?? "";
        $gender                 =   $request->gender ?? "";
        $address                =   $request->address ?? "";
        $user_id                =   $request->user_id;
        $user_id                =   (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;;
        $remarks                =   $request->remarks ?? "";
        $relation               =   $request->relation ?? "";
        $is_depend              =   $request->is_depend ?? false;
        $proffesion             =   $request->proffesion ?? "";
        $employment             =   $request->employment ?? "";
        $nationality            =   $request->nationality ?? "";
        $blood_group            =   $request->blood_group ?? "";
        $date_of_birth          =   $request->filled('date_of_birth') ? date('Y-m-d', strtotime($request->date_of_birth)) : null;
        $marriage_date          =   $request->filled('marriage_date') ? date('Y-m-d', strtotime($request->marriage_date)) : null;
        $insurance_name         =   $request->insurance_name ?? "";
        $contact_number         =   $request->contact_number ?? "";
        $maritial_status        =   $request->maritial_status ?? "";
        $health_insurance       =   $request->health_insurance ?? false;

        try {
            if ($key == "create") {
                $family_data = EmpFamily::create([
                    // 'id'                =>  $id,
                    'name'              =>  $name,
                    'title'             =>  $title,
                    'gender'            =>  $gender,
                    'address'           =>  $address,
                    'remarks'           =>  $remarks,
                    'user_id'           =>  $user_id,
                    'relation'          =>  $relation,
                    'is_depend'         =>  $is_depend,
                    'employment'        =>  $employment,
                    'proffesion'        =>  $proffesion,
                    'blood_group'       =>  $blood_group,
                    'nationality'       =>  $nationality,
                    'marriage_date'     =>  $marriage_date,
                    'date_of_birth'     =>  $date_of_birth,
                    'insurance_name'    =>  $insurance_name,
                    'contact_number'    =>  $contact_number,
                    'maritial_status'   =>  $maritial_status,
                    'health_insurance'  =>  $health_insurance,
                ]);

                if ($request->hasFile("file")) {
                    $family_data->clearMediaCollection('family-details');

                    $family_data->addMediaFromRequest('file')
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('family-details');
                }
                if ($family_data) {
                    return response()->json([
                        'status'  => true,
                        'message' => 'Family Details added successfully (Create).',
                    ], 201);
                }
            } elseif ($key == "update") {
                $family_data = EmpFamily::findOrFail($id);


                $family_data->update([
                    'name'                  => $name,
                    'title'                 => $title,
                    'gender'                => $gender,
                    'address'               => $address,
                    'remarks'               => $remarks,
                    'user_id'               => $user_id,
                    'relation'              => $relation,
                    'is_depend'             => $is_depend,
                    'employment'            => $employment,
                    'proffesion'            => $proffesion,
                    'nationality'           => $nationality,
                    'blood_group'           => $blood_group,
                    'marriage_date'         => $marriage_date,
                    'date_of_birth'         => $date_of_birth,
                    'insurance_name'        => $insurance_name,
                    'contact_number'        => $contact_number,
                    'maritial_status'       => $maritial_status,
                    'health_insurance'      => $health_insurance,
                ]);

                if ($request->hasFile("file")) {
                    $family_data->clearMediaCollection('family-details');

                    $family_data->addMediaFromRequest('file')
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('family-details');
                }
                if ($family_data) {
                    return response()->json([
                        'status'  => true,
                        'message' => 'Family Details updated successfully (Update).',
                    ], 200);
                }
            } else {
                $family_data = EmpFamily::findOrFail($id);
                $family_data->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Family Details deleted successfully.'
                ], 200);
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

    public function updateEmergencyAddress(Request $request)
    {
        try {

            $i = 1;
            foreach ($request->addresses as $address) {

                EmpAddress::updateOrCreate(
                    [
                        'user_id'           => $address['user_id'] ?? auth()->user()->id,
                        'address_type'      => 'emergency_address_' . $i,
                    ],
                    [
                        'contact_name'      => $address['contact_name'] ?? "",
                        'relation'          => $address['relation'] ?? "",
                        'city'              => $address['city'] ?? "",
                        'state'             => $address['state'] ?? "",
                        'line1'             => $address['line1'] ?? "",
                        'phone1'            => $address['phone1'] ?? "",
                        'phone2'            => $address['phone2'] ?? "",
                        'country'           => $address['country'] ?? "",
                        'pincode'           => $address['pincode'] ?? "",
                    ]
                );

                $i++;
            }

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Emergency Address added successfully.'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    public function updateQualificationDetails(UserRequest $request)
    {
        $id                             =   $request->id ?? "";
        $key                            =   $request->key ?? "create";
        $year                           =   $request->year ?? "";
        $grade                          =   $request->grade ?? "";
        $to_date                        =   $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;
        $remarks                        =   $request->remarks ?? "";
        $user_id                        =   auth()->user()->id;
        $from_date                      =   $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
        $stream_type                    =   $request->stream_type ?? "";
        // $percentage                     =   $request->percentage ?? "";
        $percentage                     =   floatval($request->percentage ?? "");
        $qualification                  =   $request->qualification ?? "";
        $specialization                 =   $request->specialization ?? "";
        $institute_name                 =   $request->institute_name ?? "";
        $university_name                =   $request->university_name ?? "";
        $date_of_passing                =   $request->filled('date_of_passing') ? date('Y-m-d', strtotime($request->date_of_passing)) : null;
        $nature_of_course               =   $request->nature_of_course ?? "";
        $duration_of_course             =   $request->duration_of_course ?? "";
        $qualification_status           =   $request->qualification_status ?? "";
        $is_highest_qualification       =   $request->is_highest_qualification ?? "";
        $qualification_course_type      =   $request->qualification_course_type ?? "";

        try {
            if ($key == "create") {
                $emp_qualification = EmpQualificationDetails::create([
                    'year'                          =>  $year,
                    'grade'                         =>  $grade,
                    'remarks'                       =>  $remarks,
                    'user_id'                       =>  $user_id,
                    'to_date'                       =>  $to_date,
                    'from_date'                     =>  $from_date,
                    'percentage'                    =>  $percentage,
                    'stream_type'                   =>  $stream_type,
                    'qualification'                 =>  $qualification,
                    'specialization'                =>  $specialization,
                    'institute_name'                =>  $institute_name,
                    'university_name'               =>  $university_name,
                    'date_of_passing'               =>  $date_of_passing,
                    'nature_of_course'              =>  $nature_of_course,
                    'duration_of_course'            =>  $duration_of_course,
                    'qualification_status'          =>  $qualification_status,
                    'is_highest_qualification'      =>  $is_highest_qualification,
                    'qualification_course_type'     =>  $qualification_course_type,
                ]);

                if ($request->hasFile("file")) {
                    $emp_qualification->clearMediaCollection('employee-qualification-document');

                    $emp_qualification->addMediaFromRequest('file')
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('employee-qualification-document');
                }
                if ($emp_qualification) {
                    return response()->json([
                        'status'  => true,
                        'message' => 'Employee Qualification Details added successfully.',
                    ], 201);
                }
            } elseif ($key == "update") {
                $emp_qualification = EmpQualificationDetails::findOrFail($id);


                $emp_qualification->update([
                    'year'                          =>  $year,
                    'grade'                         =>  $grade,
                    'remarks'                       =>  $remarks,
                    'user_id'                       =>  $user_id,
                    'to_date'                       =>  $to_date,
                    'from_date'                     =>  $from_date,
                    'percentage'                    =>  $percentage,
                    'stream_type'                   =>  $stream_type,
                    'qualification'                 =>  $qualification,
                    'specialization'                =>  $specialization,
                    'institute_name'                =>  $institute_name,
                    'university_name'               =>  $university_name,
                    'date_of_passing'               =>  $date_of_passing,
                    'nature_of_course'              =>  $nature_of_course,
                    'duration_of_course'            =>  $duration_of_course,
                    'qualification_status'          =>  $qualification_status,
                    'is_highest_qualification'      =>  $is_highest_qualification,
                    'qualification_course_type'     =>  $qualification_course_type,
                ]);

                if ($request->hasFile("file")) {
                    $emp_qualification->clearMediaCollection('employee-qualification-document');

                    $emp_qualification->addMediaFromRequest('file')
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('employee-qualification-document');
                }
                if ($emp_qualification) {
                    return response()->json([
                        'status'  => true,
                        'message' => 'Qualification updated successfully (Update).',
                    ], 200);
                }
            } else {
                $emp_qualification = EmpQualificationDetails::findOrFail($id);
                $emp_qualification->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Qualification Details deleted successfully.'
                ], 200);
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

    public function updateOtherDetails($request)
    {

        $form = $request->form ?? 0;

        switch ($form) {
            case 1:
                return $this->updateNominationDetails($request);
                break;
            case 2:
                return $this->updateSkillDetails($request);
                break;
            case 3:
                return $this->updateLanguageDetails($request);
                break;
            default:
                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'Form id missing.'
                ]);
                break;
        }
    }

    public function updateNominationDetails($request)
    {
        //
    }

    public function updateSkillDetails(Request $request)
    {
        $id                     =   $request->id ?? "";
        $key                    =   $request->key ?? "";
        try {

            if ($key == "create") {
                EmpSkill::create(
                    [
                        'user_id'           =>  $request->user_id ?? "",
                        'name'              =>  $request->name ?? "",
                        'type'              =>  $request->type ?? "",
                        'level'             =>  $request->level ?? "",
                        'effective_date'    =>  $request->effective_date ?? "",
                    ]
                );

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Skill added successfully.'
                ]);
            } elseif ($key == "update") {
                $emp_skill = EmpSkill::findOrFail($id);
                $emp_skill->update([
                    'user_id'           =>  $request->user_id ?? "",
                    'name'              =>  $request->name ?? "",
                    'type'              =>  $request->type ?? "",
                    'level'             =>  $request->level ?? "",
                    'effective_date'    =>  $request->effective_date ?? "",
                ]);
                return response()->json([
                    'status'  => true,
                    'message' => 'Employee Skill updated successfully.',
                ], 200);
            } else {
                $emp_skill = EmpSkill::findOrFail($id);
                $emp_skill->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Employee Skill deleted successfully.'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.'
            ]);
        }
    }

    public function updateLanguageDetails($request)
    {
        $id                     =   $request->id ?? "";
        $key                    =   $request->key ?? "";
        try {
            if ($key == "create") {
                EmpLanguage::create(
                    [
                        'user_id'   =>  $request->user_id ?? "",
                        'name'      =>  $request->name ?? "",
                        'read'      =>  $request->read ?? 0,
                        'write'     =>  $request->write ?? 0,
                        'speak'     =>  $request->speak ?? 0,
                        'native'    =>  $request->native ?? 0,
                    ]
                );

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Language added successfully.'
                ]);
            } elseif ($key == "update") {
                $emp_languages = EmpLanguage::findOrFail($id);
                $emp_languages->update([
                    'user_id'   =>  $request->user_id ?? "",
                    'name'      =>  $request->name ?? "",
                    'read'      =>  $request->read ?? 0,
                    'write'     =>  $request->write ?? 0,
                    'speak'     =>  $request->speak ?? 0,
                    'native'    =>  $request->native ?? 0,
                ]);
                return response()->json([
                    'status'  => true,
                    'message' => 'Employee Language updated successfully.',
                ], 200);
            } else {
                $emp_languages = EmpLanguage::findOrFail($id);
                $emp_languages->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Employee Language deleted successfully.'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.'
            ]);
        }
    }

    public function updateSeparationDetails($request)
    {
        try {

            $user_id                    =   $request->user_id ?? "";
            $remarks                    =   $request->remarks ?? "";
            $lwd_expected               =   $request->filled('lwd_expected') ? date('Y-m-d', strtotime($request->lwd_expected)) : null;
            $submission_date            =   $request->filled('submission_date') ? date('Y-m-d', strtotime($request->submission_date)) : null;
            $date_of_joining            =   $request->filled('date_of_joining') ? date('Y-m-d', strtotime($request->date_of_joining)) : null;
            $year_of_service            =   $request->filled('year_of_service') ? (int)$request->year_of_service : null;
            $lwd_after_serving_notice   =   $request->filled('lwd_after_serving_notice') ? date('Y-m-d', strtotime($request->lwd_after_serving_notice)) : null;
            // $lwd_expected               =   $request->lwd_expected ?? "";
            // $year_of_service            =   $request->year_of_service ?? "";
            // $submission_date            =   $request->submission_date ?? "";
            // $date_of_joining            =   $request->date_of_joining ?? "";
            // $lwd_after_serving_notice   =   $request->lwd_after_serving_notice ?? "";

            $user_id                    = (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;

            $emp_separation = EmpSeparation::updateOrCreate(
                [
                    'user_id'   =>  $user_id
                ],
                [
                    'remarks'                   =>  $remarks,
                    'lwd_expected'              =>  $lwd_expected,
                    'date_of_joining'           =>  $date_of_joining,
                    'submission_date'           =>  $submission_date,
                    'year_of_service'           =>  $year_of_service,
                    'lwd_after_serving_notice'  =>  $lwd_after_serving_notice,
                ]
            );

            if ($request->hasFile("file")) {

                $emp_separation->clearMediaCollection('employee-separation');

                $emp_separation->addMediaFromRequest('file')
                    ->sanitizingFileName(function ($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('employee-separation');
            }


            return response()->json([
                'status'    =>  true,
                'message'   =>  'Employee separation details updated successfully.',
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function updateDocumentDetails($request)
    {
        try {
            $document_id        = $request->document_id ?? "";
            $user_id            = $request->user_id ?? null;
            $family_id          = $request->family_id ?? null;
            $related_to         = $request->related_to ?? "";
            $related_to_ids     = $request->related_to_ids ?? null;
            $active_user_id     = $request->active_user_id ?? null;
            $document_type      = $request->document_type ?? "";
            $issue_place        = $request->issue_place ?? "";
            $document_no        = $request->document_no ?? "";
            $issue_date         = $request->filled('issue_date') ? $request->issue_date : null;
            $expiry_date        = $request->filled('expiry_date') ? $request->expiry_date : null;
            $remarks            = $request->remarks ?? "";

            // if (empty($related_to_ids)) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'No related_to_ids provided.',
            //     ], 400);
            // }

            // $employee_family_id = null;
            // $user_id = null;

            // if ($related_to == "My-Self") {
            //     if (!User::where('id', $related_to_ids)->exists()) {
            //         return response()->json([
            //             'status' => false,
            //             'message' => "User with ID $related_to_ids does not exist.",
            //         ], 400);
            //     }
            //     $user_id = $related_to_ids;
            // } else {
            //     if (!EmpFamily::where('id', $related_to_ids)->exists()) {
            //         return response()->json([
            //             'status' => false,
            //             'message' => "Employee family with ID $related_to_ids does not exist.",
            //         ], 400);
            //     }
            //     $employee_family_id = $related_to_ids;
            // }

            $emp_document = EmpDocument::updateOrCreate(
                [
                    'id'                    =>  $document_id
                ],
                [
                    'employee_family_id'    => $family_id,
                    'user_id'               => $user_id,
                    'document_type'         => $document_type,
                    'active_user_id'        => $active_user_id,
                    'issue_place'           => $issue_place,
                    'document_no'           => $document_no,
                    'issue_date'            => $issue_date,
                    'expiry_date'           => $expiry_date,
                    'remarks'               => $remarks,
                ]
            );

            if ($emp_document && $request->hasFile("files")) {
                foreach ($request->file("files") as $file) {
                    $emp_document->clearMediaCollection('employee-family-document');
                    $emp_document->addMedia($file)
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('employee-family-document');
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Employee documents details updated successfully.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function documentDelete($id)
    {
        try {

            $employee_document = EmpDocument::findOrFail($id);

            $employee_document->delete();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Employee Document deleted successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Employee Document not found.'
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'exception' =>  $e->getMessage(),
                'message'   =>  'Something went wrong.'
            ], 500);
        }
    }
    
    public function updateAssetDetails(Request $request)
    {
        $id                     =   $request->id ?? "";
        $key                    =   $request->key ?? "create";
        $sr_no                  =   $request->sr_no ?? "";
        $remarks                =   $request->remarks ?? "";
        $user_id                =   $request->user_id ?? "";
        $user_id                =   (isset($user_id) && $user_id != "") ? (int)$user_id : auth()->user()->id;
        $valid_till             =   $request->valid_till ?? "";
        $assets_name            =   $request->assets_name ?? "";
        $assign_date            =   $request->assign_date ?? "";
        $assets_type            =   $request->assets_type ?? "";
        $assets_status          =   $request->assets_status ?? "";

        try {
            if ($key == "create") {
                $emp_assets = EmpAssets::create([
                    'sr_no'                 =>  $sr_no,
                    'assets_type'           =>  $assets_type,
                    'assets_name'           =>  $assets_name,
                    'assets_status'         =>  $assets_status,
                    'assign_date'           =>  $assign_date,
                    'user_id'               =>  $user_id,
                    'valid_till'            =>  $valid_till,
                    'remarks'               =>  $remarks,
                ]);

                return response()->json([
                    'status'  => true,
                    'message' => 'Employee Assets Details added successfully (Create).',
                ], 201);
            } elseif ($key == "update") {
                $emp_assets = EmpAssets::findOrFail($id);


                $emp_assets->update([
                    'sr_no'                 =>  $sr_no,
                    'assets_type'           =>  $assets_type,
                    'assets_name'           =>  $assets_name,
                    'assets_status'         =>  $assets_status,
                    'assign_date'           =>  $assign_date,
                    'user_id'               =>  $user_id,
                    'valid_till'            =>  $valid_till,
                    'remarks'               =>  $remarks,
                ]);

                return response()->json([
                    'status'  => true,
                    'message' => 'Employee Assets updated successfully (Update).',
                ], 200);
            } else {
                $emp_assets = EmpAssets::findOrFail($id);
                $emp_assets->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Employee Assets deleted successfully.'
                ], 200);
            }

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.'
            ], 400);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function EmployeeCsvUpload(Request $request)
    {
        try {
            $password = $this->generatePassword();

            if ($request->hasFile('csv_file')) {
                $file = $request->file('csv_file');

                if ($file->isValid()) {
                    $csvFile = fopen($file->getPathname(), "r");

                    $header = fgetcsv($csvFile, 1000, ",");
                    $expectedHeader = [
                        'Employee Id',
                        'Name',
                        'Date Of Birth',
                        "Father's Name",
                        'Gender',
                        'Marital Status',
                        'Confirmation Date',
                        'Date Of Joining',
                        'Status',
                        'City',
                        'Department Name',
                        'Designation Name',
                        'State',
                        'Email Id',
                    ];

                    if ($header !== $expectedHeader) {
                        fclose($csvFile);
                        return response()->json([
                            'status' => false,
                            'message' => 'Invalid CSV file format. Please ensure that the file contains all the columns in the correct sequence.',
                        ], 400);
                    }

                    while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
                        if (empty($data[0]) || empty($data[1]) || empty($data[13])) {
                            // Skip this row and continue with the next row
                            continue;
                        }
                        $city                   = trim($data[9]) ?? null;
                        $name                   = trim($data[1]) ?? null;
                        $state                  = trim($data[12]) ?? null;
                        $status                 = strtolower(trim($data[8])) ?? null;
                        // $gender                 = $data[4] ?? null;
                        $gender                 = trim(strtolower($data[4])) ?? null;
                        $father_name            = trim($data[3]) ?? null;
                        $employee_id            = trim(strtoupper($data[0])) ?? "";
                        $office_email           = trim(strtolower($data[13])) ?? null;
                        $date_of_birth          = date('Y-m-d', strtotime($data[2]));
                        $marital_status         = trim($data[5]) ?? null;
                        $date_of_joining        = date('Y-m-d', strtotime($data[7]));
                        $department_name        = strtolower(trim($data[10])) ?? null;
                        $designation_name       = strtolower(trim($data[11])) ?? null;
                        $confirmation_date      = date('Y-m-d', strtotime($data[6]));

                        if (!in_array($status, ['active', 'deactive'])) {
                            fclose($csvFile);
                            return response()->json([
                                'status' => false,
                                'message' => 'Invalid status provided. Status must be either "active" or "deactive".',
                            ], 400);
                        }

                        // Validate 'gender' field
                        if (!in_array(strtolower($gender), ['male', 'female', 'other'])) {
                            fclose($csvFile);
                            return response()->json([
                                'status' => false,
                                'message' => 'Invalid gender provided. Gender must be either "Male", "Female", or "Other".',
                            ], 400);
                        }


                        if ($status == 'active') {
                            $status = 1;
                        } elseif ($status == 'deactive') {
                            $status = 0;
                        }

                        if (User::where('employee_id', $employee_id)->orWhere('email', $office_email)->exists()) {
                            continue;
                        }

                        $department = Department::whereRaw('LOWER(name) = ?', [$department_name])->first();
                        $department_id = $department ? $department->id : null;

                        $designation = Designation::whereRaw('LOWER(name) = ?', [$designation_name])->first();
                        $designation_id = $designation ? $designation->id : null;
                        if (!$department_id || !$designation_id) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Invalid department or designation provided.',
                            ], 400);
                        }

                        $nameParts      = explode(' ', $name);
                        $first_name     = $nameParts[0];
                        $last_name      = '';
                        $middle_name    = '';

                        if (count($nameParts) > 1) {
                            $last_name      = array_pop($nameParts);
                            $first_name     = array_shift($nameParts);
                            $middle_name    = implode(' ', $nameParts);
                        }

                        $user = User::create([
                            'honorific'         => ($gender && strtolower($gender) === 'female') ?
                                ($marital_status && strtolower($marital_status) === 'married' ? 'Mrs.' : 'Miss.') :
                                'Mr.',
                            'employee_id'       =>  $employee_id,
                            'first_name'        =>  $first_name,
                            'last_name'         =>  $last_name,
                            'middle_name'       =>  $middle_name,
                            'status'            =>  $status,
                            'email'             =>  $office_email,
                            'password'          =>  Hash::make($password),
                            'password_updated'  =>  0,
                        ]);

                        if ($user) {

                            $user_id = $user->id;
                            EmpPersonalDetail::create([
                                'user_id'           =>  $user_id,
                                'date_of_birth'     =>  $date_of_birth,
                                'gender'            =>  $gender,
                                'father_name'       =>  $father_name,
                                'marital_status'    =>  $marital_status,
                            ]);

                            EmpJoining::create([
                                'user_id'               =>  $user_id,
                                'date_of_joining'       =>  $date_of_joining,
                                'confirmation_date'     =>  $confirmation_date,
                                'office_email'          =>  $office_email,
                            ]);

                            EmpAddress::create([
                                'user_id'       =>  $user_id,
                                'city'          =>  $city,
                                'state'         =>  $state,
                            ]);

                            EmpDepartment::create([
                                'user_id' =>  $user_id,
                                'department_id' =>  $department_id,
                            ]);

                            EmpDesignation::create([
                                'user_id'           =>  $user_id,
                                'designation_id'    =>  $designation_id,
                            ]);

                            $honorific = $first_name ? "Dear $first_name" : "Dear User";

                            $emailData = [
                                'user' => [
                                    'honorific'     => ($gender && strtolower($gender) === 'female') ?
                                        ($marital_status && strtolower($marital_status) === 'married' ? 'Mrs.' : 'Miss.') :
                                        'Mr.',
                                    'last_name'     =>  $last_name,
                                    'employee_id'   =>  $employee_id,
                                    'password'      =>  $password,
                                    'email'         =>  $office_email,
                                ],
                            ];



                            Mail::to($office_email)->send(new CreateUserMail($emailData));
                        }
                    }

                    fclose($csvFile);

                    return response()->json([
                        'status' => true,
                        'message' => 'CSV file uploaded and data inserted successfully.',
                    ], 201);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed to upload CSV file.',
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No CSV file uploaded.',
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

    public function resetPassword(Request $request, $id)
    {
        $rules = [
            'id' => 'exists:users,id',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/',
            ],
        ];

        $customMessages = [
            'required'              =>  'The :attribute field is required.',
            'password.regex'        =>  'Password must have at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'confirmed'             =>  'The password confirmation does not match.',
            'id.exists'             =>  'User with this ID does not exist.',
        ];

        $this->validate($request, $rules, $customMessages);

        try {
            $user = User::where('id', $id)
                ->where('status', 1)
                ->first();

            if (!$user) {
                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'User not found.'
                ], 404);
            }


            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Password updated successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'        =>  false,
                'message'       =>  'Something went wrong.',
                'exception'     =>  $e->getMessage(),
            ], 500);
        }
    }

    public function getNewEmployeeId()
    {
        $last_emp_id = User::orderBy('id', 'desc')->first();
        $employee_id = $last_emp_id['employee_id'];

        $alphabetic_part = substr($employee_id, 0, 3);
        $numeric_part = substr($employee_id, 3);

        $new_numeric_part = (int)$numeric_part + 1;

        $new_employee_id = $alphabetic_part . sprintf("%04d", $new_numeric_part);

        return response()->json([
            'status'    =>  true,
            'message'   =>  'Unique employee id generated.',
            'data'      =>  $new_employee_id
        ], 200);
    }
}
