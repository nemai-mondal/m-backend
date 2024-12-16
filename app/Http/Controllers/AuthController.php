<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Resources\UserResource;
use App\Mail\ForgotPasswordMail;
use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    # Create a new AuthController instance.
    #
    # @return void
    public function __construct()
    {
        //
    }

    /**
     * @OA\POST(
     *     path="/v1/login",
     *     tags={"Authentication"},
     *     summary="Login user into HRMS system",
     *     operationId="loginUser",
     *     @OA\RequestBody(
     *         description="Login credentials",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"employee_id", "password"},
     *                 @OA\Property(
     *                     property="employee_id",
     *                     type="string",
     *                     default="MMT001"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     default="Passw0rd@123"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful Login",
     *         @OA\Header(
     *             header="access_token",
     *             description="JWT token after successful login. Will be used to access restricted routes.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="expires_in",
     *             description="Time in seconds after which JWT token will be invalid.",
     *             @OA\Schema(
     *                 type="Integer",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="user",
     *             description="Details of the logged in user.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTkyLjE2OC4xLjUzOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzAyNDYxMDQ1LCJleHAiOjE3MDI0NjQ2NDUsIm5iZiI6MTcwMjQ2MTA0NSwianRpIjoiQmI3NHRtbWN2TkMwQktVcyIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.xNXtrf8qgAhwyhM3pZOl6U1JsL9Fwq3Hb5ptQb0RfbM"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600),
     *             @OA\Property(
     *                  property="user",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Aman Aasim"),
     *                  @OA\Property(property="email", type="string", example="a.aman@magicminds.io"),
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
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $remember_me = $request->remember_me ?? false;
            $credentials = $request->only('employee_id', 'password');

            $user = User::where('employee_id', $credentials['employee_id'])->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Incorrect Employee ID or Password.'
                ], 400);
            }

            if ($user->status == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Yor account has been suspended. Please contact to the Admin.'
                ], 403);
            }

            /**
             * If user checked on the Remember Me check box
             * Then increase the time of JWT Token and Refresh Token expiry time
             * Time is set by the .env file or else 30 Days and 60 Days time will be added respectively. 
             */
            if($remember_me) {
                config(['jwt.ttl'           => config('jwt.remember_me_ttl')]);
                config(['jwt.refresh_ttl'   => config('jwt.remember_me_refresh_ttl')]);
            }

            if (!$token = auth()->attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Incorrect Employee ID or Password.'
                ], 400);
            }

            return $this->respondWithToken($token);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\POST(
     *     path="/v1/logout",
     *     tags={"Authentication"},
     *     summary="Logout user from HRMS system",
     *     operationId="logoutUser",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout Successfully.",
     *         @OA\Header(
     *             header="status",
     *             description="Shows that the user logged out successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Show the message to the User after logged him out successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out successfully."),
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
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Unauthorized"
     *         ),
     *     ),
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
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'status'    => true,
            'message'   => 'Logged out successfully.'
        ]);
    }

    # Refresh a token.
    #
    # @return \Illuminate\Http\JsonResponse
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    # Get the token array structure.
    #
    # @param  string $token
    #
    # @return \Illuminate\Http\JsonResponse
    protected function respondWithToken($token = null)
    {
        if(auth()->user()->password_updated == 0)
        {
            $new_request = new Request([
                'status'        =>  true,
                'email'         =>  auth()->user()->email,
                'first_login'   =>  1,
            ]);

            return $this->forgotPassword($new_request);
        }

        return response()->json([
            'token_type'                => 'bearer',
            'access_token'              => $token,
            'expires_in'                => config('jwt.ttl') * 60,
            'refresh_token_expires_in'  => config('jwt.refresh_ttl') * 60,
            'user'                      => new UserResource(auth()->user())
        ]);
    }

    /**
     * @OA\POST(
     *     path="/v1/refresh-jwt",
     *     tags={"Authentication"},
     *     summary="Refresh JWT Token",
     *     operationId="refreshToken",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Refresh JWT Token.",
     *         @OA\Header(
     *             header="token_type",
     *             description="Show the Token Type.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="access_token",
     *             description="JWT Token.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="expires_in",
     *             description="JWT Token expiry time in seconds.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="refresh_token_expires_in",
     *             description="JWT Refresh Token expiry time in seconds.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token_type", type="boolean", example="bearer"),
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTkyLjE2OC4xLjUzOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzAyNDYxMDQ1LCJleHAiOjE3MDI0NjQ2NDUsIm5iZiI6MTcwMjQ2MTA0NSwianRpIjoiQmI3NHRtbWN2TkMwQktVcyIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.xNXtrf8qgAhwyhM3pZOl6U1JsL9Fwq3Hb5ptQb0AZXS"),
     *             @OA\Property(property="refresh_token_expires_in", type="boolean", example="86400"),
     *             @OA\Property(property="expires_in", type="boolean", example="604800"),
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
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Unauthorized"
     *         ),
     *     ),
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
     * Refresh the token.
     * Invalidate the previous token and generate a new JWT Token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshJWTToken()
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json(['Unauthenticated.'], 401);
            }
            $token = JWTAuth::refresh($token);

            return response()->json([
                'token_type'                => 'bearer',
                'access_token'              => $token,
                'expires_in'                => auth()->factory()->getTTL() * 60,
                'refresh_token_expires_in'  => config('jwt.refresh_ttl') * 60,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['Unauthenticated.'], 401);
        }
    }

    public function forgotPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            $user = User::where('email', strtolower(trim($request->email)))
                ->where('status', 1)
                ->first();

            if (!$user) {
                return response()->json([
                    'status'    =>  false,
                    'message'   => 'Invalid email id.'
                ], 400);
            }

            $token = bin2hex(random_bytes(32));

            ResetPassword::where('user_id', $user->id)->delete();

            $expired_at = Carbon::now()->addMinutes(config('auth.reset_password_time'));

            $saveToken = ResetPassword::create([
                'token'             =>  $token,
                'user_id'           =>  $user->id,
                'expired_at'        =>  $expired_at
            ]);

            if ($saveToken) {
                // Create a Carbon instance for the current time in IST
                $tokenExpTimeInSeconds =  $expired_at->timestamp;

                // Send the forgot password link to the user's email
                $resetLink = config('frontend.base_url') . "/reset-password?token={$token}&stamp={$tokenExpTimeInSeconds}";

                /**
                 * If user is login for the first time
                 * He needs to update his password first
                 * And email will be not sent, instead token will be sent in response
                 */
                if($request->first_login === true || $request->first_login === 1) {
                    return response()->json([
                        'token'         =>  $token,
                        'timestamp'     =>  $tokenExpTimeInSeconds,
                        'first_login'   =>  1,
                    ], 201);
                }

                Mail::to($user->email)->send(new ForgotPasswordMail($resetLink));

                return response()->json([
                    'status'        =>  true,
                    'message'       => 'A Password reset link has been sent to your registered email.',
                ], 201);
            }

            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong',
            ], 400);
        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    public function resetPassword(PasswordRequest $request)
    {
        try {
            $token  = ResetPassword::where('token', $request->token)->first();
            $valid  = $token && Carbon::parse($token->expired_at)->isFuture();

            if (!$valid) {
                return response()->json([
                    'status'    =>  false,
                    'message'   => 'Invalid or expired URL.'
                ], 400);
            }

            $user = User::find($token['user_id']);
            /**
             * If user is login for the first time
             * Validate that the password sent over the email is valid or not
             */
            if($request->first_login == 1) {

                $oldPassword = $request->input('old_password');
                $userLogin   = User::where('employee_id', $user['employee_id'])->first();
                
                if (!$userLogin || !password_verify($oldPassword, $userLogin->password)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Old Password is incorrect.'
                    ], 400);
                }
            }

            $user = User::find($token->user_id);

            if (Hash::check(trim($request->password), $user->password)) {
                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'New password can not be same as old password.'
                ], 400);
            }

            $user->password = Hash::make(trim($request->password));

            if($request->first_login == 1) {
                $user->password_updated = 1;
            }
            
            if ($user->save() && $token->delete()) {
                return response()->json([
                    'status'    =>  true,
                    'message'   => 'Password updated successfully.'
                ], 201);
            }

            return response()->json([
                'status'    =>  false,
                'message'   => 'Something went wrong.'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 400);
        }
    }

    public function validateToken(Request $request)
    {
        try {
            $token = ResetPassword::where('token', $request->token)->first();

            $valid = $token && Carbon::parse($token->expired_at)->isFuture();

            if (!$valid) {
                return response()->json([
                    'status'    =>  false,
                    'message'   => 'Invalid or expired URL.'
                ], 400);
            }

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Valid Token.',
                'first_login'   =>  $request->first_login ?? false,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 400);
        }
    }
}
