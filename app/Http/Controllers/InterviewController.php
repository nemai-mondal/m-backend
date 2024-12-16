<?php

namespace App\Http\Controllers;

use App\Http\Requests\InterviewRequest;
use App\Http\Resources\InterviewCollection;
use App\Http\Resources\InterviewResource;
use App\Mail\EmploymentVerificationMail;
use App\Mail\InterviewAssignmentMail;
use App\Models\Interview;
use App\Models\Interviewer;
use App\Models\InterviewFeedback;
use App\Models\InterviewHrFeedback;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewScheduledMail;
use App\Models\Designation;
use App\Models\InterviewAssignment;
use App\Models\InterviewAssignmentFeedback;
use App\Models\InterviewSchedule;
use App\Models\InterviewScheduleFeedback;
use App\Models\InterviewScreening;
use App\Models\InterviewUpcoming;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class InterviewController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/interview/list",
     *     tags={"Interview"},
     *     summary="Interview List.",
     *     operationId="listInterview",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Interview list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Interview list fetched successfully.",
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
     *             description="Contains the object of Interview.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interviews list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="user_id", type="integer", example=5),
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

        $interviews = Interview::with(
            'department',
            'designation',
            'candidateAddedBy',
            'screeningFeedback',
            'assignments',
            'assignmentFeedbacks',
            'scheduledInterviews',
            'scheduledInterviewFeedbacks',
            'hrHeadFeedback'
        )
            ->get();

        // $interviews = Interview::all();


        return response()->json([
            'staus'     =>  true,
            'message'   =>  'Interviews list fetched.',
            'data'      =>  $interviews,
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
     *     path="/v1/interview/create",
     *     tags={"Interview"},
     *     summary="Add new candidate.",
     *     operationId="createLeaveType",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Add new candidate.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"name", "email"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="designation",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="source_name",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="source_link",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                   property="total_experience",
     *                   type="Integer",
     *                  ),
     *                 @OA\Property(
     *                   property="job_profile",
     *                   type="String",
     *                  ),
     *                 @OA\Property(
     *                   property="previous_company",
     *                   type="String",
     *                  ),
     *                 @OA\Property(
     *                   property="previous_company_gross",
     *                   type="String",
     *                  ),
     *                 @OA\Property(
     *                   property="current_expectation",
     *                   type="String",
     *                  ),
     *                 @OA\Property(
     *                   property="current_agreed_gross",
     *                   type="String",
     *                  ),
     *                 @OA\Property(
     *                   property="highest_qualification",
     *                   type="String",
     *                  ),
     *                 @OA\Property(
     *                   property="notice_period",
     *                   type="Integer",
     *                  ),
     *                 @OA\Property(
     *                   property="primary_skill",
     *                   type="String",
     *                  ),
     *                 @OA\Property(
     *                   property="secondary_skill",
     *                   type="String",
     *                  ),
     *                 @OA\Property(
     *                   property="remarks",
     *                   type="String",
     *                  ),
     *                 @OA\Property(
     *                   property="file",
     *                   type="String",
     *                  ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Candidate added successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user when the Candidate added successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example=" Candidate added successfully."),
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
    public function store(InterviewRequest $request)
    {
        try {

            $interview = Interview::create([
                'user_id'                       =>  auth()->user()->id,
                'name'                          =>  $request->name ?? "",
                'email'                         =>  $request->email ?? "",
                'phone'                         =>  $request->phone ?? "",
                'applied_designation_id'        =>  $request->applied_designation_id ?? "",
                'applied_department_id'         =>  $request->applied_department_id ?? "",
                'source_name'                   =>  $request->source_name ?? "",
                'source_link'                   =>  $request->source_link ?? "",
                'total_experience'              =>  $request->total_experience ?? "",
                'previous_designation'          =>  $request->previous_designation ?? "",
                'previous_company'              =>  $request->previous_company ?? "",
                'current_company'               =>  $request->current_company ?? "",
                'current_ctc'                   =>  $request->current_ctc ?? "",
                'expected_ctc'                  =>  $request->expected_ctc ?? "",
                'highest_qualification'         =>  $request->highest_qualification ?? "",
                'notice_period'                 =>  $request->notice_period ?? "",
                'primary_skill'                 =>  $request->primary_skill ?? "",
                'secondary_skill'               =>  $request->secondary_skill ?? "",
                'remarks'                       =>  $request->remarks ?? "",
                'updated_by'                    =>  auth()->user()->id,
            ]);

            if ($request->hasFile("file")) {
                $interview->clearMediaCollection('interview-resume');

                $interview->addMediaFromRequest('file')
                    ->sanitizingFileName(function ($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('interview-resume');
            }

            return response()->json([
                'status'        =>  true,
                'message'       =>  'Candidate added successfully.',
                'interview_id'  =>  $interview->id,
            ], 201);
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
     *     path="/v1/interview/show/{id}",
     *     tags={"Leave Ratio"},
     *     summary="Find Interview Details",
     *     operationId="showInterview",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Interview ID",
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
     *             description="Status indicating that the Interview found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the Interview.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Interview.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interview Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
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

            $interview = Interview::with(
                'scheduledInterviews',
                'department',
                'designation',
                'candidateAddedBy',
                'screeningFeedback',
                'assignments',
                'assignmentFeedbacks',
                'scheduledInterviewFeedbacks',
                'hrHeadFeedback',
                'interviewers'
            )
                ->findOrFail($id);

            /**
             * Add Interviewers for Scheduled Interviews
             */
            $schedules      = $interview['scheduledInterviews'] ?? [];
            $interview_id   = $interview['id'];

            $m = 0;
            foreach ($schedules as $schedule) {

                $interviewers = Interviewer::with('user')
                    ->where('schedule_id', $schedule['id'])
                    ->get();

                $interview['scheduledInterviews'][$m]['interviewers'] = $interviewers;
                $m++;
            }


            /**
             * Add Interviewers for Assignment
             */
            $assignments    = $interview['assignments'] ?? [];

            $i = 0;
            foreach ($assignments as $assignment) {

                $interview_round    = $assignment['interview_round'];
                $interviewers = Interviewer::with('user')
                    ->where('interview_round', $interview_round)
                    ->where('interview_id', $interview_id)
                    ->get();

                $interview['assignments'][$i]['interviewers'] = $interviewers;
                $i++;
            }

            return response()->json([
                'staus'     =>  true,
                'message'   =>  'Interviews list fetched.',
                'data'      =>  $interview,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'staus'     =>  false,
                'message'   =>  'Interview details not found.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'staus'     =>  false,
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
     *     path="/v1/interview/update/{id}",
     *     tags={"Interview"},
     *     summary="Update Interview Details.",
     *     operationId="updateInterview",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Interview ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update Interview.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"name", "email", "interview_link", "date", "designation_id"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="interview_link",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="date",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="designation_id",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="link",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                   property="phone",
     *                   type="string",
     *                  ),
     *                 @OA\Property(
     *                   property="source",
     *                   type="string",
     *                  ),
     *                 @OA\Property(
     *                   property="remarks",
     *                   type="string",
     *                  ),
     *                 @OA\Property(
     *                   property="interview_at",
     *                   type="string",
     *                  ),
     *                 @OA\Property(
     *                   property="primary_skill",
     *                   type="string",
     *                  ),
     *                 @OA\Property(
     *                   property="notice_period",
     *                   type="string",
     *                  ),
     *                 @OA\Property(
     *                   property="secondary_skill",
     *                   type="string",
     *                  ),
     *                 @OA\Property(
     *                   property="total_experience",
     *                   type="string",
     *                  ),
     *                 @OA\Property(
     *                   property="previous_company",
     *                   type="string",
     *                  ),
     *                 @OA\Property(
     *                   property="current_expectation",
     *                   type="string",
     *                  ),
     *                 @OA\Property(
     *                   property="current_agreed_gross",
     *                   type="string",
     *                  ),
     *                 @OA\Property(
     *                   property="previous_company_gross",
     *                   type="string",
     *                  ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Interview updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Interview.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interview updated successfully."),
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
    public function update(Request $request, $id)
    {
        try {

            $interview = Interview::findOrFail($id)->update([
                'name'                          =>  $request->name ?? null,
                'email'                         =>  $request->email ?? null,
                'phone'                         =>  $request->phone ?? null,
                'user_id'                       =>  auth()->user()->id,
                'remarks'                       =>  $request->remarks ?? null,
                'source_link'                   =>  $request->source_link ?? null,
                'source_name'                   =>  $request->source_name ?? null,
                'primary_skill'                 =>  $request->primary_skill ?? null,
                'notice_period'                 =>  $request->notice_period ?? null,
                'secondary_skill'               =>  $request->secondary_skill ?? null,
                'total_experience'              =>  $request->total_experience ?? null,
                'previous_company'              =>  $request->previous_company ?? null,
                'current_expectation'           =>  $request->current_expectation ?? null,
                'previous_designation'          =>  $request->previous_designation ?? null,
                'current_agreed_gross'          =>  $request->current_agreed_gross ?? null,
                'highest_qualification'         =>  $request->highest_qualification ?? null,
                'previous_company_gross'        =>  $request->previous_company_gross ?? null,
                'applied_designation_id'        =>  $request->applied_designation_id ?? null,
                'applied_department_id'         =>  $request->applied_department_id ?? null,
                'updated_by'                    =>  auth()->user()->id,
            ]);


            if ($request->hasFile("file")) {

                $interview = Interview::find($id);
                $interview->clearMediaCollection('interview-resume');
                $interview->addMediaFromRequest('file')
                    ->sanitizingFileName(function ($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('interview-resume');
            }

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Interview updated successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Interview details not found.',
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
     *     path="/v1/interview/delete/{id}",
     *     tags={"Interview"},
     *     summary="Delete Interview.",
     *     operationId="deleteInterview",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Interview ID",
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
     *             description="Status indicating that the Interview was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Interview.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interview deleted successfully."),
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

            $interview = Interview::findOrFail($id);
            $interview->delete();

            Interviewer::where('interview_id', $id)->delete();
            $interview->clearMediaCollection('interview');

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Interview deleted successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Interview details not found.'
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
     * @OA\GET(
     *     path="/v1/interview/joining",
     *     tags={"Interview"},
     *     summary="Joining Dashboard.",
     *     operationId="joiningInterview",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         description="Number of results",
     *         @OA\Schema(type="integer", default=10),
     *     ),
     *     @OA\Parameter(
     *         name="order_by",
     *         in="query",
     *         description="Column Name",
     *         required=false, 
     *         @OA\Schema(type="string", default="joining_date"),
     *     ),
     *     @OA\Parameter(
     *         name="order_type",
     *         in="query",
     *         description="Column Name",
     *         required=false,
     *         @OA\Schema(type="string", default="asc"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidate joining list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Interview list fetched successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Show the message to the user after fetching the list.",
     *             @OA\Schema(
     *                 type="Integer",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Interview.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interview list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                  ),
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthorized"
     *          ),
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
     *     ),
     * ),
     */
    public function joining(Request $request)
    {

        $limit          = $request->limit ?? 10;
        $orderBy        = $request->order_by ?? 'joining_date';
        $orderType      = $request->order_type ?? 'asc';

        try {

            // $interviews = InterviewHrFeedback::with('interview')
            $interviews = InterviewHrFeedback::with('interview')
                ->whereHas('interview', function ($query) {
                    $query->whereNotNull('id');
                })
                ->where('status', 'hired')
                ->where('joining_date', '>=', date('Y-m-d'))
                ->orderBy($orderBy, $orderType)
                ->limit($limit)
                ->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Interviews list.',
                'data'      =>  $interviews ?? [],
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
     * @OA\GET(
     *     path="/v1/interview/upcoming",
     *     tags={"Interview"},
     *     summary="Interview Dashboard.",
     *     operationId="dashboardInterview",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         description="Number of results",
     *         @OA\Schema(type="integer", default=10),
     *     ),
     *     @OA\Parameter(
     *         name="order_by_primary",
     *         in="query",
     *         description="Column Name",
     *         required=false,
     *         @OA\Schema(type="string", default="interview_date"),
     *     ),
     *     @OA\Parameter(
     *         name="order_by_secondary",
     *         in="query",
     *         description="Column Name",
     *         required=false,
     *         @OA\Schema(type="string", default="interview_time"),
     *     ),
     *     @OA\Parameter(
     *         name="order_type_primary",
     *         in="query",
     *         description="Column Name",
     *         required=false,
     *         @OA\Schema(type="string", default="asc"),
     *     ),
     *     @OA\Parameter(
     *         name="order_type_secondary",
     *         in="query",
     *         description="Column Name",
     *         required=false,
     *         @OA\Schema(type="string", default="asc"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Interview list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Interview list fetched successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Show the message to the user after fetching the list.",
     *             @OA\Schema(
     *                 type="Integer",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Interview.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interview list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                  ),
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthorized"
     *          ),
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
     *     ),
     * ),
     */
    public function upcoming(Request $request)
    {

        $limit                  = $request->limit ?? 10;
        $orderByPrimary         = $request->order_by_primary ?? 'interview_date';
        $orderTypePrimary       = $request->order_type_primary ?? 'asc';
        $orderBySecondary       = $request->order_by_secondary ?? 'interview_time';
        $orderTypeSecondary     = $request->order_type_secondary ?? 'asc';

        try {

            $interviews = InterviewSchedule::with('interview')
                ->where(function ($query) {
                    $query->whereNull('status')
                        ->orWhere('status', '');
                })
                ->where('interview_date', '>=', date('Y-m-d'))
                ->orderBy($orderByPrimary, $orderTypePrimary)
                ->orderBy($orderBySecondary, $orderTypeSecondary)
                ->limit($limit)
                ->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Interviews list.',
                'data'      =>  $interviews ?? [],
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
     * @OA\GET(
     *     path="/v1/interview/search",
     *     tags={"Interview"},
     *     summary="Interview Search.",
     *     operationId="searchInterview",
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
     *     @OA\Parameter(
     *         name="order_by",
     *         in="query",
     *         description="Column Name",
     *         required=false,
     *         @OA\Schema(type="string", default="id"),
     *     ),
     *     @OA\Parameter(
     *         name="order_type",
     *         in="query",
     *         description="ASC / DESC",
     *         required=false,
     *         @OA\Schema(type="string", default="desc"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Interview list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Interview list fetched successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Show the message to the user after fetching the list.",
     *             @OA\Schema(
     *                 type="Integer",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Interview.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interview list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                  ),
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthorized"
     *          ),
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
     *     ),
     * ),
     */

    // public function search(Request $request)
    // {
    //     $perPage                    = $request->per_page == "" ? 10 : $request->per_page;
    //     $orderBy                    = $request->order_by == "" ? 'id' : $request->order_by;
    //     $end_date                   = $request->end_date ?? "";
    //     $orderType                  = $request->order_type == "" ? 'desc' : $request->order_type;
    //     $start_date                 = $request->start_date ?? "";
    //     $currentPage                = $request->current_page == "" ? 1 : $request->current_page;
    //     $candidate_name             = $request->candidate_name ?? "";
    //     $applied_designation        = $request->applied_designation ?? "";
    //     $applied_designation_id     = $request->applied_designation_id ?? "";

    //     try {
    //         $interviews = InterviewSchedule::with(
    //             // 'user',
    //             'interview',
    //             'assignment'
    //         )
    //         ->orderBy($orderBy, $orderType);

    //         if ($candidate_name != "") {
    //             $interviews = $interviews->whereHas('interview', function ($query) use ($candidate_name) {
    //                 $query->where('name', 'like', '%' . $candidate_name . '%');
    //             });
    //         }

    //         if ($applied_designation != "") {
    //             $interviews = $interviews->whereHas('interview', function ($query) use ($applied_designation) {
    //                 $query->where('applied_designation', 'like', '%' . $applied_designation . '%');
    //             });
    //         }

    //         if ($applied_designation_id != "") {
    //             $interviews = $interviews->whereHas('interview', function ($query) use ($applied_designation_id) {
    //                 $query->where('applied_designation_id', 'like', '%' . $applied_designation_id . '%');
    //             });
    //         }

    // if ($start_date != "" && $end_date != "") {
    //     $interviews = $interviews->whereBetween('interview_date', [$start_date, $end_date]);
    // } elseif ($start_date != "") {
    //     $interviews = $interviews->where('interview_date', '>=', $start_date);
    // } elseif ($end_date != "") {
    //     $interviews = $interviews->where('interview_date', '<=', $end_date);
    // }

    //         $interviews = $interviews->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

    //         return response()->json([
    //             'status'    =>  true,
    //             'message'   =>  'Interviews list.',
    //             'data'      =>  $interviews ?? [],
    //         ], 200);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status'    =>  false,
    //             'message'   =>  'Something went wrong.',
    //             'exception' =>  $e->getMessage() 
    //         ], 500);
    //     }
    // }

    public function search(Request $request)
    {

        $perPage                    = $request->per_page == "" ? 10 : $request->per_page;
        $orderBy                    = $request->order_by == "" ? 'id' : $request->order_by;
        $end_date                   = $request->end_date ?? "";
        $orderType                  = $request->order_type == "" ? 'desc' : $request->order_type;
        $start_date                 = $request->start_date ?? "";
        $currentPage                = $request->current_page == "" ? 1 : $request->current_page;
        $candidate_name             = $request->candidate_name ?? "";
        $applied_designation        = $request->applied_designation ?? "";
        $applied_designation_id     = $request->applied_designation_id ?? "";

        try {

            $interviews = Interview::with(
                'department',
                'designation',
                'candidateAddedBy',
                'screeningFeedback',
                'assignments',
                'assignmentFeedbacks',
                'scheduledInterviews',
                'scheduledInterviewFeedbacks',
                'hrHeadFeedback'
            )
                ->orderBy($orderBy, $orderType);

            if ($candidate_name != "") {
                $interviews = $interviews->where('name', 'like', '%' . $candidate_name . '%');
            }

            if ($applied_designation != "") {
                $interviews = $interviews->where('applied_designation', 'like', '%' . $applied_designation . '%');
            }

            if ($applied_designation_id != "") {
                $interviews = $interviews->where('applied_designation_id', 'like', '%' . $applied_designation_id . '%');
            }

            if ($start_date != "") {
                $interviews = $interviews->where('created_at', '>=', $start_date);
            }

            if ($end_date != "") {
                $interviews = $interviews->where('created_at', '<=', $end_date);
            }

            $interviews = $interviews->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Interviews list.',
                'data'      =>  $interviews ?? [],
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    //     public function search(Request $request)
    // {
    //     $perPage = $request->per_page == "" ? 10 : $request->per_page;
    //     $orderBy = $request->order_by == "" ? 'id' : $request->order_by;
    //     $end_date = $request->end_date ?? "";
    //     $orderType = $request->order_type == "" ? 'desc' : $request->order_type;
    //     $start_date = $request->start_date ?? "";
    //     $currentPage = $request->current_page == "" ? 1 : $request->current_page;
    //     $candidate_name = $request->candidate_name ?? "";
    //     $applied_designation = $request->applied_designation ?? "";
    //     $applied_designation_id = $request->applied_designation_id ?? "";

    //     try {
    //         $interviews = Interview::with(
    //             'department',
    //             'designation',
    //             'candidateAddedBy',
    //             'screeningFeedback',
    //             'assignments',
    //             'assignmentFeedbacks',
    //             'scheduledInterviews',
    //             'scheduledInterviewFeedbacks',
    //             'hrHeadFeedback'
    //         )->orderBy($orderBy, $orderType);

    //         if ($candidate_name != "") {
    //             $interviews = $interviews->where('name', 'like', '%' . $candidate_name . '%');
    //         }

    //         if ($applied_designation != "") {
    //             $interviews = $interviews->where('applied_designation', 'like', '%' . $applied_designation . '%');
    //         }

    //         if ($applied_designation_id != "") {
    //             $interviews = $interviews->where('applied_designation_id', 'like', '%' . $applied_designation_id . '%');
    //         }

    //         if ($start_date != "" && $end_date != "") {
    //             $interviews = $interviews->whereHas('scheduledInterviews', function ($query) use ($start_date, $end_date) {
    //                 $query->whereBetween('interview_date', [$start_date, $end_date]);
    //             });
    //         } elseif ($start_date != "") {
    //             $interviews = $interviews->whereHas('interviewSchedule', function ($query) use ($start_date) {
    //                 $query->where('interview_date', '>=', $start_date);
    //             });
    //         } elseif ($end_date != "") {
    //             $interviews = $interviews->whereHas('interviewSchedule', function ($query) use ($end_date) {
    //                 $query->where('interview_date', '<=', $end_date);
    //             });
    //         }

    //         $interviews = $interviews->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Interviews list.',
    //             'data' => $interviews ?? [],
    //         ], 200);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something went wrong.',
    //             'exception' => $e->getMessage()
    //         ], 500);
    //     }
    // }


    /**
     * @OA\POST(
     *     path="/v1/interview/assignment",
     *     tags={"Interview"},
     *     summary="Interview Screening.",
     *     operationId="InterviewScreeningDetails",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Interview Screening.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"interview_id"},
     *                 @OA\Property(
     *                     property="interview_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="remarks",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="attitude",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="is_suitable",
     *                     type="Boolean"
     *                 ),
     *                 @OA\Property(
     *                     property="work_exp_assessment",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="interpersonal_skill_score",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="communication_skill_score",
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
     *             description="Status indicates that candidate screening feedback updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the candidate screening details.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Candidate screening feedback updated successfully."),
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
    public function assignment(Request $request)
    {
        // return $request->all();
        try {

            $interviewers = $request->interviewers ?? [];
            $interviewers_list = [];
            if (var_dump($interviewers) != "array" && var_dump($interviewers) != "object") {
                $interviewers_list = explode(",", $interviewers);
            }
            // return $interviewers_list;
            Interview::findOrFail($request->interview_id)->update([
                'updated_by' =>  auth()->user()->id,
            ]);

            $candidate = Interview::findOrFail($request->interview_id);

            $assignment = InterviewAssignment::updateOrCreate(
                [
                    "id" =>  $request->assignment_id ?? null,
                ],
                [
                    'user_id'           =>  auth()->user()->id,
                    'interview_id'      =>  $request->interview_id ?? null,
                    'name'              =>  $request->name ?? "",
                    'status'            =>  $request->status ?? null,
                    'details'           =>  $request->details ?? "",
                    'remarks'           =>  $request->remarks ?? null,
                    'assignment_date'   =>  $request->assignment_date ?? "",
                    'submission_date'   =>  isset($request->submission_date) && $request->submission_date != 'null' ? $request->submission_date : null,
                    // 'submission_date'   =>  $request->filled('submission_date') ? $request->submission_date : null,
                    'interview_round'   =>  $request->interview_round ?? "",
                ]
            );


            
            // Delete all the interviewers first
            $old_interviewers = Interviewer::where('interview_id', $request->interview_id)
                ->where('interview_round', $request->interview_round)
                ->where('assignment_id', $request->assignment_id ?? $assignment['id'])
                ->get();
            foreach ($old_interviewers as $interviewer) {

                $interviewer->delete();
            }





            foreach ($interviewers_list as $user) {

                $interviewer = Interviewer::withTrashed()
                    ->where('user_id', $user)
                    ->where('interview_id', $request->interview_id)
                    ->where('interview_round', $request->interview_round)
                    ->where('assignment_id', $request->assignment_id ?? $assignment['id'])
                    ->first();


                if ($interviewer) {
                    if ($interviewer->trashed()) {
                        $interviewer->restore();
                    }
                } else {
                    Interviewer::create(
                        [
                            'user_id'           =>  $user ?? null,
                            'interview_id'      =>  $request->interview_id ?? null,
                            'interview_round'   =>  $request->interview_round ?? null,
                            'assignment_id'     =>  $request->assignment_id ?? $assignment['id']
                        ]
                    );
                }
            }

            if ($request->hasFile("file")) {
                $assignment->clearMediaCollection('interview-assignment');

                $assignment->addMediaFromRequest('file')
                    ->sanitizingFileName(function ($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('interview-assignment');
            }

            $emailData = [
                'name'              =>  $candidate->name,
                'details'           =>  $request->details,
                'submission_date'   =>  $request->submission_date,
                'assignment_link'   =>  $assignment->getMedia("interview-assignment")->first()->original_url ?? ""
            ];

            // Send email to the candidate for Interview Assignment.
            Mail::to($candidate->email)->send(new InterviewAssignmentMail($emailData));

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Interview Assignment sent successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Invalid interview id.',
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
     * @OA\POST(
     *     path="/v1/interview/screening",
     *     tags={"Interview"},
     *     summary="Interview Screening.",
     *     operationId="InterviewScreeningDetails",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Interview Screening.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"interview_id"},
     *                 @OA\Property(
     *                     property="interview_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="remarks",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="attitude",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="is_suitable",
     *                     type="Boolean"
     *                 ),
     *                 @OA\Property(
     *                     property="work_exp_assessment",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="interpersonal_skill_score",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="communication_skill_score",
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
     *             description="Status indicates that candidate screening feedback updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the candidate screening details.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Candidate screening feedback updated successfully."),
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
    public function screening(Request $request)
    {
        try {

            Interview::findOrFail($request->interview_id)->update([
                'updated_by' =>  auth()->user()->id,
            ]);

            InterviewScreening::updateOrCreate(
                [
                    'interview_id'      =>  $request->interview_id,
                ],
                [
                    'status'                        =>  $request->status ?? null,
                    'user_id'                       =>  auth()->user()->id,
                    'remarks'                       =>  $request->remarks ?? null,
                    'attitude'                      =>  $request->attitude ?? null,
                    'is_suitable'                   =>  $request->is_suitable ?? null,
                    'work_exp_assessment'           =>  $request->work_exp_assessment ?? null,
                    'interpersonal_skill_score'     =>  $request->interpersonal_skill_score ?? null,
                    'communication_skill_score'     =>  $request->communication_skill_score ?? null,
                ]
            );

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Candidate Screening details updated successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Interview details not found.',
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
     * @OA\POST(
     *     path="/v1/interview/assignment-feedback",
     *     tags={"Interview"},
     *     summary="Update Interviewer Feedback.",
     *     operationId="InterviewerFeedbackInterview",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Interview Feedback.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"interview_id", "interview_round", "interview_method"},
     *                 @OA\Property(
     *                     property="interview_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="interview_round",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="interview_method",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="feedback",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
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
     *             description="Status indicates that Interview feedback updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Interview Feedback.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interview feedback updated successfully."),
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
    public function assignmentFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feedback_submission_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {

            InterviewAssignment::findOrFail($request->assignment_id);
            Interview::findOrFail($request->interview_id);

            InterviewAssignmentFeedback::updateOrCreate(
                [
                    'user_id'           =>  auth()->user()->id,
                    'interview_id'      =>  $request->interview_id,
                    'assignment_id'     =>  $request->assignment_id,
                ],
                [
                    'status'                                =>  $request->status ?? null,
                    'rating'                                =>  $request->rating ?? "",
                    'feedback'                              =>  $request->feedback ?? "",
                    'overall_rating'                        =>  $request->overall_rating ?? "",
                    'feedback_submission_date'              =>  $request->feedback_submission_date ?? "",
                ]
            );

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Interview Assignment Feedback added successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Invalid Interview or Assignment id.',
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
     * @OA\POST(
     *     path="/v1/interview/interviewer-feedback",
     *     tags={"Interview"},
     *     summary="Update Interviewer Feedback.",
     *     operationId="InterviewerFeedbackInterview",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Interview Feedback.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"interview_id", "interview_round", "interview_method"},
     *                 @OA\Property(
     *                     property="interview_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="interview_round",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="interview_method",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="feedback",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
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
     *             description="Status indicates that Interview feedback updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Interview Feedback.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interview feedback updated successfully."),
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
    public function interviewerSchedule(Request $request)
    {
        try {


            Interview::findOrFail($request->interview_id)->update([
                'updated_by' =>  auth()->user()->id,
            ]);

            $schedule = InterviewSchedule::updateOrCreate(
                [
                    'id'                        =>  $request->interview_schedule_id ?? null,
                ],
                [
                    'user_id'                   =>  auth()->user()->id,
                    'interview_id'              =>  $request->interview_id ?? null,
                    'interview_mode'            =>  $request->interview_mode ?? null,
                    'interview_date'            =>  $request->interview_date ?? null,
                    'interview_time'            =>  $request->interview_time ?? null,
                    'interview_duration'        =>  $request->interview_duration ?? null,
                    'interview_platform'        =>  $request->interview_platform ?? null,
                    'interview_url'             =>  $request->interview_url ?? null,
                    'interview_agenda'          =>  $request->interview_agenda ?? null,
                    'assignment_given'          =>  $request->assignment_given ?? null,
                    'assignment_id'             =>  $request->assignment_id == "" ? null : $request->assignment_id,
                    'related_to'                =>  $request->related_to == "" ? null : $request->related_to,
                    'reminder'                  =>  $request->reminder ?? null,
                    'status'                    =>  $request->status ?? null,
                ]
            );

            foreach ($request->interviewers as $user) {
                Interviewer::updateOrCreate(
                    [
                        'user_id'           =>  $user ?? null,
                        'interview_id'      =>  $request->interview_id ?? null,
                        'interview_round'   =>  $request->interview_round ?? null,
                        'schedule_id'       =>  $request->interview_schedule_id ?? $schedule['id']
                    ]
                );
            }

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Interview Scheduled successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Interview details not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\GET(
     *     path="/v1/interview/assignment-feedback",
     *     tags={"Interview"},
     *     summary="Update Interviewer Feedback.",
     *     operationId="InterviewerFeedbackInterview",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Interview Feedback.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"interview_id", "interview_round", "interview_method"},
     *                 @OA\Property(
     *                     property="interview_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="interview_round",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="interview_method",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="feedback",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
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
     *             description="Status indicates that Interview feedback updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Interview Feedback.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interview feedback updated successfully."),
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

    public function interviewerScheduleFeedback(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'overall_rating' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $interview              = Interview::findOrFail($request->interview_id);
            $interviewSchedule      = InterviewSchedule::findOrFail($request->interview_schedule_id);

            InterviewScheduleFeedback::updateOrCreate(
                [
                    'user_id'                       => auth()->user()->id,
                    'interview_id'                  => $request->interview_id,
                    'interview_schedule_id'         => $request->interview_schedule_id,
                ],
                [
                    'status'                    => $request->status ?? null,
                    'code_quality'              => $request->code_quality ?? "",
                    'overall_rating'            => $request->overall_rating,
                    'problem_solving'           => $request->problem_solving ?? "",
                    'technical_feedback'        => $request->technical_feedback ?? "",
                    'additional_feedback'       => $request->additional_feedback ?? "",
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Interview Feedback added successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Interview or Interview Schedule id.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\GET(
     *     path="/v1/interview/interviewer-feedback",
     *     tags={"Interview"},
     *     summary="Update Interviewer Feedback.",
     *     operationId="InterviewerFeedbackInterview",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Interview Feedback.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"interview_id", "interview_round", "interview_method"},
     *                 @OA\Property(
     *                     property="interview_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="interview_round",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="interview_method",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="feedback",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
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
     *             description="Status indicates that Interview feedback updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Interview Feedback.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interview feedback updated successfully."),
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
    public function interviewHrFeedback(Request $request)
    {
        try {

            Interview::findOrFail($request->interview_id)->update([
                'updated_by' =>  auth()->user()->id,
            ]);

            InterviewHrFeedback::updateOrCreate(
                [
                    'user_id'                   =>  auth()->user()->id,
                    'interview_id'              =>  $request->interview_id ?? "",
                ],
                [
                    'status'                    =>  $request->status ?? null,
                    'strength'                  =>  $request->strength ?? "",
                    'weakness'                  =>  $request->weakness ?? "",
                    'feedback'                  =>  $request->feedback ?? "",
                    'joining_date'              =>  $request->joining_date ?? null,
                    'interview_date'            =>  $request->interview_date ?? null,
                    'overall_assessment'        =>  $request->overall_assessment ?? "",
                    'cultural_fit_assessment'   =>  $request->cultural_fit_assessment ?? "",
                ]
            );

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Final Feedback updated successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Interview details not found.',
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
     * @OA\POST(
     *     path="/v1/interview/interview-hr-feedback",
     *     tags={"Interview"},
     *     summary="HR Interview Feedback.",
     *     operationId="hrFeedbackInterview",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="HR Interview Feedback.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"interview_id"},
     *                 @OA\Property(
     *                     property="interview_id",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="remarks",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="attitude",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="suitable",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="comments",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="final_status",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="joining_date",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="interviewer_name",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="total_experience",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="domain_knowledge",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="technical_knowledge",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="interpersonal_skill",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="communication_skill",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="total_experience_observed",
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
     *             description="Status indicates that HR Interview Feedback updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after HR Interview Feedback update.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="HR Interview Feedback updated successfully."),
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
    public function employmentVerification(Request $request)
    {

        try {

            $data = [
                'name'  =>  $request->name ?? ""
            ];

            Mail::to($request->email ?? "")->send(new EmploymentVerificationMail($data));

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Interview Feedback added successfully.'
            ], 201);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\POST(
     *     path="/v1/interview/multidelete",
     *     tags={"Interview"},
     *     summary="Delete Multiple Interviews",
     *     operationId="deleteMultipleInterviews",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Array of interview IDs",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="interview_ids",
     *                     type="array",
     *                     @OA\Items(type="integer", format="int64")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Interviews deleted successfully."),
     *             @OA\Property(property="not_found_id", type="string", example="Interviews with IDs 22, 2 not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Bad Request"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\MediaType(
     *             mediaType="string",   
     *             example="Unauthenticated"
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Server Error"
     *         )
     *     )
     * )
     */

    public function multiDelete(Request $request)
    {
        try {
            $ids = $request->interview_ids ?? [];
            $deletedInterviewNames = [];
            $notFoundInterviewNames = [];

            foreach ($ids as $id) {
                $interview = Interview::find($id);

                if ($interview) {
                    $deletedInterviewNames[] = $interview->name;
                    $interview->delete();
                } else {
                    $notFoundInterviewNames[] = $id;
                }
            }

            $deletedMessage = 'Interviews deleted successfully: ' . implode(', ', $deletedInterviewNames);
            $notFoundMessage = 'Interviews with IDs: ' . implode(', ', $notFoundInterviewNames) . ' not found.';

            return response()->json([
                'status'          => true,
                'message'         => $deletedMessage,
                'not_found_ids'   => $notFoundMessage,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }




    public function upcomingAndPreviousInterview(Request $request)
    {
        $view = $request->view ?? "all";
        $perPage = $request->per_page == "" ? 10 : $request->per_page;
        $orderBy = $request->order_by == "" ? 'id' : $request->order_by;
        $end_date = $request->end_date ?? "";
        $orderType = $request->order_type == "" ? 'desc' : $request->order_type;
        $start_date = $request->start_date ?? "";
        $currentPage = $request->current_page == "" ? 1 : $request->current_page;
        $candidate_name = $request->candidate_name ?? "";
        $applied_designation = $request->applied_designation ?? "";
        $applied_designation_id = $request->applied_designation_id ?? "";

        try {
            $now = Carbon::now('Asia/Kolkata');

            $upcomingInterviewsQuery = Interview::query();

            if ($view == 'upcoming' || $view == 'all') {
                $upcomingInterviewsQuery->whereHas('scheduledInterviews', function ($query) use ($now) {
                    $query->whereDate('interview_date', '>', $now->toDateString())
                        ->orWhere(function ($query) use ($now) {
                            $query->whereDate('interview_date', '=', $now->toDateString())
                                ->whereTime('interview_time', '>=', $now->toTimeString());
                        });
                });
            }

            if ($view == 'previous' || $view == 'all') {
                $upcomingInterviewsQuery->orWhereHas('scheduledInterviews', function ($query) use ($now) {
                    $query->whereDate('interview_date', '<', $now->toDateString())
                        ->orWhere(function ($query) use ($now) {
                            $query->whereDate('interview_date', '=', $now->toDateString())
                                ->whereTime('interview_time', '<', $now->toTimeString());
                        });
                });
            }

            if ($candidate_name != "") {
                $upcomingInterviewsQuery->where('name', 'like', '%' . $candidate_name . '%');
            }

            if ($applied_designation != "") {
                $designation = Designation::whereRaw("LOWER(name) = ?", [strtolower($applied_designation)])->first();

                if ($designation) {
                    $applied_designation_id = $designation->id;
                    $upcomingInterviewsQuery->where('applied_designation_id', $applied_designation_id);
                } else {
                    $upcomingInterviewsQuery->where('applied_designation_id', null);
                }
            }

            if ($applied_designation_id != "") {
                $upcomingInterviewsQuery->where('applied_designation_id', 'like', '%' . $applied_designation_id . '%');
            }

            if ($start_date != "") {
                $upcomingInterviewsQuery->where('created_at', '>=', $start_date);
            }

            if ($end_date != "") {
                $upcomingInterviewsQuery->where('created_at', '<=', $end_date);
            }

            $upcomingInterviews = $upcomingInterviewsQuery
                ->with('department', 'designation', 'candidateAddedBy', 'screeningFeedback', 'assignments', 'assignmentFeedbacks', 'scheduledInterviews', 'scheduledInterviewFeedbacks', 'hrHeadFeedback')
                ->orderBy($orderBy, $orderType)
                ->paginate($perPage, ['*'], 'page', $currentPage)
                ->withQueryString();

            $upcomingInterviewsObject = $upcomingInterviews->setCollection($upcomingInterviews->getCollection());

            return response()->json([
                'status' => true,
                'message' => $view == 'previous' ? 'Previous Interviews list.' : 'Upcoming Interviews list.',
                'data' => $upcomingInterviewsObject ?? new \stdClass(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }
}
