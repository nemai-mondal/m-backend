<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Router;
use Illuminate\Support\Facades\Route;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/



$router->group(['prefix' => 'v1/'], function () use ($router) {

    // Open OR Public APIs
    $router->post('login',              'AuthController@login');
    $router->post('forgot-password',    'AuthController@forgotPassword');

    // Restricted APIs
    $router->post('refresh-jwt',        'AuthController@refreshJWTToken');
    $router->post('reset-password',     'AuthController@resetPassword');
    $router->post('validate-token',     'AuthController@validateToken');
    $router->post('logout', ['middleware' => 'auth:api', 'uses' => 'AuthController@logout']);


    // Restricted APIs - In Alphabet Order
    $router->group(['middleware' => 'auth:api'], function () use ($router) {

        $router->group(['prefix' => 'activity/'], function () use ($router) {

            $router->get('list',                            'ActivityController@index');
            $router->get('search',                          'ActivityController@search');
            $router->post('create',                         'ActivityController@store');
            $router->get('show/{id}',                       'ActivityController@show');
            $router->put('update/{id}',                     'ActivityController@update');
            $router->delete('delete/{id}',                  'ActivityController@destroy'); 
        });
        
        $router->group(['prefix' => 'amendment/'], function () use ($router) { 
            $router->post('create',                         'MediaController@store');
            $router->get('list',                            'MediaController@index');
            $router->get('search',                          'MediaController@search');
            $router->get('show/{id}',                       'MediaController@show');
            $router->put('update/{id}',                     'MediaController@update');
            $router->post('multi-delete',                   'MediaController@multiDelete');
            $router->delete('delete/{id}',                  'MediaController@destroy');
            $router->put('publish-amendment/{id}',          'MediaController@publishAmendment');
            $router->get('publish-amendment-list',          'MediaController@publishAmendmentList');
        });
        
        $router->group(['prefix' => 'attendance/'], function () use ($router) {
            
            $router->get('list',                            'AttendanceController@index');
            $router->get('cron',                            'AttendanceController@cron');
            $router->get('search',                          'AttendanceController@search');
            $router->post('assign',                         'AttendanceController@assign');
            $router->get('show/{id}',                       'AttendanceController@show');

            $router->group(['prefix' => 'working-hour'], function() use ($router) {

                $router->post('create',                     'AttendanceController@workingHoursCreate');
            });

            $router->group(['prefix' => 'regularization'], function() use ($router) {

                $router->post('create',                     'AttendanceController@regularizationCreate');
            });
        });
        
        $router->group(['prefix' => 'candidate/'], function () use ($router) {

            $router->get('list',                            'CandidateController@index');
            $router->get('search',                          'CandidateController@search');
            $router->post('create',                         'CandidateController@store');
        });

        $router->group(['prefix' => 'client/'], function () use ($router) {

            $router->get('list',                            'ClientController@index');
            $router->get('search',                          'ClientController@search');
            $router->post('create',                         'ClientController@store');
            $router->get('show/{id}',                       'ClientController@show');
            $router->put('update/{id}',                     'ClientController@update');
            $router->delete('delete/{id}',                  'ClientController@destroy');
        });

        $router->group(['prefix' => 'department/'], function () use ($router) {

            $router->get('list',                            'DepartmentController@index');
            $router->get('search',                          'DepartmentController@search');
            $router->post('create',                         'DepartmentController@store');
            $router->get('show/{id}',                       'DepartmentController@show');
            $router->get('get-department-id',               'DepartmentController@getDepartmentId');
            $router->put('update/{id}',                     'DepartmentController@update');
            $router->delete('delete/{id}',                  'DepartmentController@destroy');
            $router->get('get-user/{id}',                   'DepartmentController@getUsersByDepartment');
        });
        
        $router->group(['prefix' => 'designation/'], function () use ($router) {
            
            $router->get('list',                            'DesignationController@index');
            $router->get('search',                          'DesignationController@search');
            $router->post('create',                         'DesignationController@store');
            $router->get('show/{id}',                       'DesignationController@show');
            $router->put('update/{id}',                     'DesignationController@update');
            $router->get('get-user/{id}',                   'DesignationController@getUsersByDesignation');
            $router->delete('delete/{id}',                  'DesignationController@destroy');
        });
        
        $router->group(['prefix' => 'employment-type/'], function () use ($router) {
            
            $router->post('create', 'EmploymentTypeController@store');
            $router->get('list',    'EmploymentTypeController@index');
        });

        $router->group(['prefix' => 'event-wish/'], function () use ($router) {
            $router->get('list',                            'EventWishController@index');
            $router->get('search',                          'EventWishController@search');
            $router->post('create',                         'EventWishController@store');
            $router->get('show/{id}',                       'EventWishController@show');
            $router->get('my-wishesh',                      'EventWishController@myWishesh');
        });

        $router->group(['prefix' => 'holiday/'], function () use ($router) {

            $router->get('list',                            'HolidayController@index');
            $router->get('search',                          'HolidayController@search');
            $router->post('create',                         'HolidayController@store');
            $router->get('show/{id}',                       'HolidayController@show');
            $router->put('update/{id}',                     'HolidayController@update');
            $router->delete('delete/{id}',                  'HolidayController@destroy');
            $router->get('upcoming_holiday',                'HolidayController@upcoming_holiday');
            $router->post('upload-csv',                     'HolidayController@csv_upload');
        });
        
        $router->group(['prefix' => 'hr-announcement/'], function () use ($router) {

            $router->get('list',                            'HRController@index');
            $router->get('search',                          'HRController@search');
            $router->post('create',                         'HRController@store');
            $router->get('show/{id}',                       'HRController@show');
            $router->put('update/{id}',                     'HRController@update');
            $router->delete('delete/{id}',                  'HRController@destroy');
        });

        $router->group(['prefix' => 'interview/'], function () use ($router) {
            
            $router->get('list',                            'InterviewController@index');
            $router->get('search',                          'InterviewController@search');
            $router->get('joining',                         'InterviewController@joining');
            $router->post('create',                         'InterviewController@store');
            $router->get('upcoming',                        'InterviewController@upcoming');
            $router->get('show/{id}',                       'InterviewController@show');
            $router->post('screening',                      'InterviewController@screening');
            $router->post('assignment',                     'InterviewController@assignment');
            $router->put('update/{id}',                     'InterviewController@update');
            $router->delete('delete/{id}',                  'InterviewController@destroy');
            $router->post('multi-delete',                   'InterviewController@multiDelete');
            $router->post('interview-schedule',             'InterviewController@interviewerSchedule');
            $router->post('assignment-feedback',            'InterviewController@assignmentFeedback');
            $router->post('interviewer-feedback',           'InterviewController@interviewerFeedback');
            $router->post('interview-hr-feedback',          'InterviewController@interviewHrFeedback');
            $router->post('employment-verification',        'InterviewController@employmentVerification');
            $router->get('upcoming-previous-interview',     'InterviewController@upcomingAndPreviousInterview');
            $router->post('interview-schedule-feedback',    'InterviewController@interviewerScheduleFeedback');
        });

        $router->group(['prefix' => 'leave-application/'], function () use ($router) {

            $router->get('list',                            'LeaveApplicationController@index');
            $router->get('search',                          'LeaveApplicationController@search');
            $router->post('create',                         'LeaveApplicationController@store');
            $router->post('review',                         'LeaveApplicationController@review');
            $router->post('balance',                        'LeaveApplicationController@leaveBalance');
            $router->post('dashboard',                      'LeaveApplicationController@dashboard');
            // $router->get('show/{id}',                       'LeaveApplicationController@show');
            // $router->put('update/{id}',                     'LeaveApplicationController@update');
            // $router->delete('delete/{id}',                  'LeaveApplicationController@destroy');
        });

        $router->group(['prefix' => 'leave-ratio/'], function () use ($router) {

            $router->get('list',                            'LeaveRatioController@index');
            $router->get('search',                          'LeaveRatioController@search');
            $router->post('create',                         'LeaveRatioController@store');
            $router->get('show/{id}',                       'LeaveRatioController@show');
            $router->put('update/{id}',                     'LeaveRatioController@update');
            $router->delete('delete/{id}',                  'LeaveRatioController@destroy');
        });

        $router->group(['prefix' => 'leave-type/'], function () use ($router) {

            $router->get('list',                            'LeaveTypeController@index');
            $router->get('search',                          'LeaveTypeController@search');
            $router->post('create',                         'LeaveTypeController@store');
            $router->get('show/{id}',                       'LeaveTypeController@show');
            $router->put('update/{id}',                     'LeaveTypeController@update');
            $router->delete('delete/{id}',                  'LeaveTypeController@destroy');
        });

        $router->group(['prefix' => 'motivational-quote/'], function () use ($router) {

            $router->get('list',                           'MotivationalQuoteController@index');
            $router->get('search',                         'MotivationalQuoteController@search');
            $router->post('create',                        'MotivationalQuoteController@store');
            $router->get('show/{id}',                      'MotivationalQuoteController@show');
            $router->put('update/{id}',                    'MotivationalQuoteController@update');
            $router->delete('delete/{id}',                 'MotivationalQuoteController@destroy');
        });

        $router->group(['prefix' => 'permission/'], function () use ($router) {

            $router->get('list',                            'PermissionController@index');
            $router->get('search',                          'PermissionController@search');
            $router->post('create',                         'PermissionController@store');
            $router->get('show/{id}',                       'PermissionController@show');
            $router->put('update/{id}',                     'PermissionController@update');
            $router->delete('delete/{id}',                  'PermissionController@destroy');
        });

        $router->group(['prefix' => 'project/'], function () use ($router) {

            $router->get('list',                            'ProjectController@index');
            $router->get('search',                          'ProjectController@search');
            $router->post('create',                         'ProjectController@store');
            $router->get('show/{id}',                       'ProjectController@show');
            $router->put('update/{id}',                     'ProjectController@update');
            $router->get('sales-search',                    'ProjectController@salesSearch');
            $router->delete('delete/{id}',                  'ProjectController@destroy');
            $router->delete('document/delete/{id}',         'ProjectController@deleteDocument');
            $router->get('get-project/{id}',                'ProjectController@getProjectByClient');
        });

        $router->group(['prefix' => 'role/'], function () use ($router) {

            $router->get('list',                            'RoleController@index');
            $router->get('search',                          'RoleController@search');
            $router->post('create',                         'RoleController@store');
            $router->get('show/{id}',                       'RoleController@show');
            $router->put('update/{id}',                     'RoleController@update');
            $router->delete('delete/{id}',                  'RoleController@destroy');
        });

        $router->group(['prefix' => 'role-permission/'], function () use ($router) {

            $router->get('list',                            'RolePermissionController@index');
            $router->get('search',                          'RolePermissionController@search');
            $router->post('create',                         'RolePermissionController@store');
            $router->get('show/{id}',                       'RolePermissionController@show');
            $router->put('update/{id}',                     'RolePermissionController@update');
            $router->delete('delete/{id}',                  'RolePermissionController@destroy');
        });

        $router->group(['prefix' => 'shift/'], function () use ($router) {

            $router->get('list',                            'ShiftController@index');
            $router->get('search',                          'ShiftController@search');
            $router->post('create',                         'ShiftController@store');
            $router->get('show/{id}',                       'ShiftController@show');
            $router->put('update/{id}',                     'ShiftController@update');
            $router->delete('delete/{id}',                  'ShiftController@destroy');
        });

        $router->group(['prefix' => 'shift_rule/'], function () use ($router) {

            $router->get('list',                            'ShiftRuleController@index');
            $router->get('search',                          'ShiftRuleController@search');
            $router->post('create',                         'ShiftRuleController@store');
            $router->get('show/{id}',                       'ShiftRuleController@show');
            $router->put('update/{id}',                     'ShiftRuleController@update');
            $router->delete('delete/{id}',                  'ShiftRuleController@destroy');
        });

        $router->group(['prefix' => 'technology/'], function () use ($router) {

            $router->get('list',                            'TechnologyController@index');
            $router->get('search',                          'TechnologyController@search');
            $router->post('create',                         'TechnologyController@store');
            $router->get('show/{id}',                       'TechnologyController@show');
            $router->put('update/{id}',                     'TechnologyController@update');
            $router->delete('delete/{id}',                  'TechnologyController@destroy');
        });

        $router->group(['prefix' => 'timelog/'], function () use ($router) {

            $router->get('list',                            'TimeLogController@index');
            $router->get('search',                          'TimeLogController@search');
            $router->post('create',                         'TimeLogController@store');
            $router->get('show/{id}',                       'TimeLogController@show');
            $router->get('attendance',                      'TimeLogController@attendance');
            // $router->delete('delete/{id}',                  'TimeLogController@destroy');
            // $router->put('update/{id}',                     'TimeLogController@update');
        });

        $router->group(['prefix' => 'user/'], function () use ($router) {

            $router->get('list',                            'UserController@index');
            $router->get('search',                          'UserController@search');
            $router->get('details',                         'UserController@details');
            $router->post('create',                         'UserController@store');
            $router->post('onboard',                        'UserController@onboard');
            $router->get('birthday',                        'UserController@upcomingBirthdays');
            $router->get('show/{id}',                       'UserController@show');
            $router->put('update/{id}',                     'UserController@update');
            $router->post('upload-csv',                     'UserController@EmployeeCsvUpload');
            $router->get('celebration',                     'UserController@getCelebrationsList');
            $router->post('assign-role',                    'UserController@assignRole');
            $router->post('remove-role',                    'UserController@removeRole');
            $router->delete('delete/{id}',                  'UserController@destroy');
            $router->put('update-profile',                  'UserController@updateProfile');
            $router->get('workanniversary',                 'UserController@upcomingCelebrations');
            $router->get('new-employee-id',                 'UserController@getNewEmployeeId');
            $router->post('assign-permission',              'UserController@assignPermission');
            $router->post('change-status/{id}',             'UserController@changeStatus');
            $router->delete('document-delete/{id}',         'UserController@documentDelete');
            $router->post('reset-password/{id}',            'UserController@resetpassword');
            $router->put('verify-adhaar/{id}',              'UserController@verifyAdhaarCard');
            $router->put('verify-pan/{id}',                 'UserController@verifyPanCard');
            $router->put('verify-voter/{id}',               'UserController@verifyVoterCard');
            $router->put('verify-driving-license/{id}',     'UserController@verifyDrivingLicense');
            $router->put('verify-passport/{id}',            'UserController@verifyPassport');
            $router->post('updateProfilepicture',           'UserController@updateProfilepicture');
            $router->patch('update-password/{id}',          'UserController@updatePassword');
        });

        $router->group(['prefix' => 'worklog/'], function () use ($router) {

            $router->get('list',                            'WorklogController@index');
            $router->get('search',                          'WorklogController@search');
            $router->post('create',                         'WorklogController@store');
            $router->get('show/{id}',                       'WorklogController@show');
            $router->get('team/{id}',                       'WorklogController@team');
            // $router->get('download',                        'WorklogController@download');
            // $router->put('update/{id}',                     'WorklogController@update');
            // $router->delete('delete/{id}',                  'WorklogController@destroy');
        });

        $router->group(['prefix' => 'task-target/'], function () use ($router) {

            $router->group(['prefix' => 'hr/'], function () use ($router) {

                $router->get('list',                            'HRTaskController@index');
                $router->post('create',                         'HRTaskController@store');
                // $router->get('search',                          'TechnologyController@search');
                // $router->get('show/{id}',                       'TechnologyController@show');
                // $router->put('update/{id}',                     'TechnologyController@update');
                // $router->delete('delete/{id}',                  'TechnologyController@destroy');
            });
        });




    });
});