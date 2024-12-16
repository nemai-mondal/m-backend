<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'activity_create',
                'menu' => 'activity',
            ],
            [
                'name' => 'activity_update',
                'menu' => 'activity',
            ],
            [
                'name' => 'activity_view',
                'menu' => 'activity',
            ],
            [
                'name' => 'activity_delete',
                'menu' => 'activity',
            ],
            // [
            //     'name' => 'activity_deactivate',
            //     'menu' => 'activity',
            // ],
            [
                'name' => 'interview_create',
                'menu' => 'interview',
            ],
            [
                'name' => 'interview_update',
                'menu' => 'interview',
            ],
            [
                'name' => 'interview_view',
                'menu' => 'interview',
            ],
            [
                'name' => 'interview_delete',
                'menu' => 'interview',
            ],
            // [
            //     'name' => 'interview_deactivate',
            //     'menu' => 'interview',
            // ],
            [
                'name' => 'client_create',
                'menu' => 'client',
            ],
            [
                'name' => 'client_update',
                'menu' => 'client',
            ],
            [
                'name' => 'client_view',
                'menu' => 'client',
            ],
            [
                'name' => 'client_delete',
                'menu' => 'client',
            ],
            // [
            //     'name' => 'client_deactivate',
            //     'menu' => 'client',
            // ],
            [
                'name' => 'department_create',
                'menu' => 'department',
            ],
            [
                'name' => 'department_update',
                'menu' => 'department',
            ],
            [
                'name' => 'department_view',
                'menu' => 'department',
            ],
            [
                'name' => 'department_delete',
                'menu' => 'department',
            ],
            // [
            //     'name' => 'department_deactivate',
            //     'menu' => 'department',
            // ],
            [
                'name' => 'designation_create',
                'menu' => 'designation',
            ],
            [
                'name' => 'designation_update',
                'menu' => 'designation',
            ],
            [
                'name' => 'designation_view',
                'menu' => 'designation',
            ],
            [
                'name' => 'designation_delete',
                'menu' => 'designation',
            ],
            // [
            //     'name' => 'designation_deactivate',
            //     'menu' => 'designation',
            // ],
            [
                'name' => 'employment_type_create',
                'menu' => 'employment_type',
            ],
            [
                'name' => 'employment_type_update',
                'menu' => 'employment_type',
            ],
            [
                'name' => 'employment_type_view',
                'menu' => 'employment_type',
            ],
            [
                'name' => 'employment_type_delete',
                'menu' => 'employment_type',
            ],
            // [
            //     'name' => 'employment_type_deactivate',
            //     'menu' => 'employment_type',
            // ],
            [
                'name' => 'holiday_create',
                'menu' => 'holiday',
            ],
            [
                'name' => 'holiday_update',
                'menu' => 'holiday',
            ],
            [
                'name' => 'holiday_view',
                'menu' => 'holiday',
            ],
            [
                'name' => 'holiday_delete',
                'menu' => 'holiday',
            ],
            // [
            //     'name' => 'holiday_deactivate',
            //     'menu' => 'holiday',
            // ],
            [
                'name' => 'hr_announcement_create',
                'menu' => 'hr_announcement',
            ],
            [
                'name' => 'hr_announcement_update',
                'menu' => 'hr_announcement',
            ],
            [
                'name' => 'hr_announcement_view',
                'menu' => 'hr_announcement',
            ],
            [
                'name' => 'hr_announcement_delete',
                'menu' => 'hr_announcement',
            ],
            // [
            //     'name' => 'hr_announcement_deactivate',
            //     'menu' => 'hr_announcement',
            // ],
            [
                'name' => 'leave_policy_create',
                'menu' => 'leave_policy',
            ],
            [
                'name' => 'leave_policy_update',
                'menu' => 'leave_policy',
            ],
            [
                'name' => 'leave_policy_view',
                'menu' => 'leave_policy',
            ],
            [
                'name' => 'leave_policy_delete',
                'menu' => 'leave_policy',
            ],
            // [
            //     'name' => 'leave_policy_deactivate',
            //     'menu' => 'leave_policy',
            // ],
            [
                'name' => 'leave_type_create',
                'menu' => 'leave_type',
            ],
            [
                'name' => 'leave_type_update',
                'menu' => 'leave_type',
            ],
            [
                'name' => 'leave_type_view',
                'menu' => 'leave_type',
            ],
            [
                'name' => 'leave_type_delete',
                'menu' => 'leave_type',
            ],
            // [
            //     'name' => 'leave_type_deactivate',
            //     'menu' => 'leave_type',
            // ],
            [
                'name' => 'motivational_quote_create',
                'menu' => 'motivational_quote',
            ],
            [
                'name' => 'motivational_quote_update',
                'menu' => 'motivational_quote',
            ],
            [
                'name' => 'motivational_quote_view',
                'menu' => 'motivational_quote',
            ],
            [
                'name' => 'motivational_quote_delete',
                'menu' => 'motivational_quote',
            ],
            // [
            //     'name' => 'motivational_quote_deactivate',
            //     'menu' => 'motivational_quote',
            // ],
            // [
            //     'name' => 'permission_create',
            //     'menu' => 'permission',
            // ],
            // [
            //     'name' => 'permission_update',
            //     'menu' => 'permission',
            // ],
            // [
            //     'name' => 'permission_view',
            //     'menu' => 'permission',
            // ],
            // [
            //     'name' => 'permission_delete',
            //     'menu' => 'permission',
            // ],
            // [
            //     'name' => 'permission_deactivate',
            //     'menu' => 'permission',
            // ],
            [
                'name' => 'project_create',
                'menu' => 'project',
            ],
            [
                'name' => 'project_update',
                'menu' => 'project',
            ],
            [
                'name' => 'project_view',
                'menu' => 'project',
            ],
            [
                'name' => 'project_delete',
                'menu' => 'project',
            ],
            // [
            //     'name' => 'project_deactivate',
            //     'menu' => 'project',
            // ],
            [
                'name' => 'role_create',
                'menu' => 'role',
            ],
            [
                'name' => 'role_update',
                'menu' => 'role',
            ],
            [
                'name' => 'role_view',
                'menu' => 'role',
            ],
            [
                'name' => 'role_delete',
                'menu' => 'role',
            ],
            // [
            //     'name' => 'role_deactivate',
            //     'menu' => 'role',
            // ],
            [
                'name' => 'shift_create',
                'menu' => 'shift',
            ],
            [
                'name' => 'shift_update',
                'menu' => 'shift',
            ],
            [
                'name' => 'shift_view',
                'menu' => 'shift',
            ],
            [
                'name' => 'shift_delete',
                'menu' => 'shift',
            ],
            // [
            //     'name' => 'shift_deactivate',
            //     'menu' => 'shift',
            // ],
            [
                'name' => 'shift_rule_create',
                'menu' => 'shift_rule',
            ],
            [
                'name' => 'shift_rule_update',
                'menu' => 'shift_rule',
            ],
            [
                'name' => 'shift_rule_view',
                'menu' => 'shift_rule',
            ],
            [
                'name' => 'shift_rule_delete',
                'menu' => 'shift rule',
            ],
            // [
            //     'name' => 'shift_rule_deactivate',
            //     'menu' => 'shift_rule',
            // ],
            [
                'name' => 'technology_create',
                'menu' => 'technology',
            ],
            [
                'name' => 'technology_update',
                'menu' => 'technology',
            ],
            [
                'name' => 'technology_view',
                'menu' => 'technology',
            ],
            [
                'name' => 'technology_delete',
                'menu' => 'technology',
            ],
            // [
            //     'name' => 'technology_deactivate',
            //     'menu' => 'technology',
            // ],
            [
                'name' => 'user_create',
                'menu' => 'user',
            ],
            [
                'name' => 'user_update',
                'menu' => 'user',
            ],
            [
                'name' => 'user_view',
                'menu' => 'user',
            ],
            // [
            //     'name' => 'user_delete',
            //     'menu' => 'user',
            // ],
            [
                'name' => 'user_deactivate',
                'menu' => 'user',
            ],
            [
                'name' => 'user_reset_password',
                'menu' => 'user',
            ],
            [
                'name' => 'worklog_create',
                'menu' => 'worklog',
            ],
            [
                'name' => 'worklog_update',
                'menu' => 'worklog',
            ],
            [
                'name' => 'worklog_view',
                'menu' => 'worklog',
            ],
            [
                'name' => 'worklog_delete',
                'menu' => 'worklog',
            ],
            // [
            //     'name' => 'worklog_deactivate',
            //     'menu' => 'worklog',
            // ],
            [
                'name' => 'timelog_create',
                'menu' => 'timelog',
            ],
            [
                'name' => 'timelog_update',
                'menu' => 'timelog',
            ],
            [
                'name' => 'timelog_view',
                'menu' => 'timelog',
            ],
            [
                'name' => 'timelog_delete',
                'menu' => 'timelog',
            ],
            // [
            //     'name' => 'timelog_deactivate',
            //     'menu' => 'timelog',
            // ],
            [
                'name' => 'leave_create',
                'menu' => 'leave',
            ],
            [
                'name' => 'leave_update',
                'menu' => 'leave',
            ],
            [
                'name' => 'leave_view',
                'menu' => 'leave',
            ],
            [
                'name' => 'leave_delete',
                'menu' => 'leave',
            ],
            // [
            //     'name' => 'leave_deactivate',
            //     'menu' => 'leave',
            // ],
            [
                'name' => 'leave_status_update',
                'menu' => 'leave',
            ],
            [
                'name' => 'amendment_create',
                'menu' => 'amendment',
            ],
            [
                'name' => 'amendment_update',
                'menu' => 'amendment',
            ],
            [
                'name' => 'amendment_view',
                'menu' => 'amendment',
            ],
            [
                'name' => 'amendment_delete',
                'menu' => 'amendment',
            ],
            // [
            //     'name' => 'amendment_deactivate',
            //     'menu' => 'amendment',
            // ],
            [
                'name' => 'attendance_create',
                'menu' => 'attendance',
            ],
            [
                'name' => 'attendance_update',
                'menu' => 'attendance',
            ],
            [
                'name' => 'attendance_view',
                'menu' => 'attendance',
            ],
            [
                'name' => 'attendance_delete',
                'menu' => 'attendance',
            ],
            // [
            //     'name' => 'attendance_deactivate',
            //     'menu' => 'attendance',
            // ],
            [
                'name' => 'attendance_status_update',
                'menu' => 'attendance',
            ],
            [
                'name' => 'user_role_assign',
                'menu' => 'user_role',
            ],
            [
                'name' => 'user_role_update',
                'menu' => 'user_role',
            ],
            [
                'name' => 'user_role_view',
                'menu' => 'user_role',
            ],
            [
                'name' => 'user_role_delete',
                'menu' => 'user_role',
            ],
            // [
            //     'name' => 'user_role_deactivate',
            //     'menu' => 'user_role',
            // ],
            [
                'name' => 'user_permission_assign',
                'menu' => 'user_permission',
            ],
            [
                'name' => 'user_permission_update',
                'menu' => 'user_permission',
            ],
            [
                'name' => 'user_permission_view',
                'menu' => 'user_permission',
            ],
            [
                'name' => 'user_permission_delete',
                'menu' => 'user_permission',
            ],
            // [
            //     'name' => 'user_permission_deactivate',
            //     'menu' => 'user_permission',
            // ],
            [
                'name' => 'candidate_create',
                'menu' => 'candidate',
            ],
            [
                'name' => 'candidate_update',
                'menu' => 'candidate',
            ],
            [
                'name' => 'candidate_view',
                'menu' => 'candidate',
            ],
            [
                'name' => 'candidate_delete',
                'menu' => 'candidate',
            ],
            // [
            //     'name' => 'candidate_deactivate',
            //     'menu' => 'candidate',
            // ],
            [
                'name' => 'hr_task_create',
                'menu' => 'hr_task',
            ],
            [
                'name' => 'hr_task_update',
                'menu' => 'hr_task',
            ],
            [
                'name' => 'hr_task_view',
                'menu' => 'hr_task',
            ],
            [
                'name' => 'hr_task_delete',
                'menu' => 'hr_task',
            ],
            // [
            //     'name' => 'hr_task_deactivate',
            //     'menu' => 'hr_task',
            // ],
            [
                'name' => 'sales_task_create',
                'menu' => 'sales_task',
            ],
            [
                'name' => 'sales_task_update',
                'menu' => 'sales_task',
            ],
            [
                'name' => 'sales_task_view',
                'menu' => 'sales_task',
            ],
            [
                'name' => 'sales_task_delete',
                'menu' => 'sales_task',
            ],
            // [
            //     'name' => 'sales_task_deactivate',
            //     'menu' => 'sales_task',
            // ],
            [
                'name' => 'marketing_task_create',
                'menu' => 'marketing_task',
            ],
            [
                'name' => 'marketing_task_update',
                'menu' => 'marketing_task',
            ],
            [
                'name' => 'marketing_task_view',
                'menu' => 'marketing_task',
            ],
            [
                'name' => 'marketing_task_delete',
                'menu' => 'marketing_task',
            ],
            // [
            //     'name' => 'marketing_task_deactivate',
            //     'menu' => 'marketing_task',
            // ],
            [
                'name' => 'development_task_create',
                'menu' => 'development_task',
            ],
            [
                'name' => 'development_task_update',
                'menu' => 'development_task',
            ],
            [
                'name' => 'development_task_view',
                'menu' => 'development_task',
            ],
            [
                'name' => 'development_task_delete',
                'menu' => 'development_task',
            ],
            // [
            //     'name' => 'development_task_deactivate',
            //     'menu' => 'development_task',
            // ],
            [
                'name' => 'master_work_log_view',
                'menu' => 'master_work_log'
            ],
            [
                'name' => 'leave_approval_view',
                'menu' => 'leave_approval'
            ],
            [
                'name' => 'punchinout',
                'menu' => 'dashboard'
            ],
            [
                'name' => 'attendence_and_activity',
                'menu' => 'dashboard'
            ],
            [
                'name' => 'quote',
                'menu' => 'dashboard'
            ],
            [
                'name' => 'holiday_and_leave',
                'menu' => 'dashboard'
            ],
            [
                'name' => 'hr_announcement',
                'menu' => 'dashboard'
            ],
            [
                'name' => 'celebrating_events',
                'menu' => 'dashboard'
            ],
            [
                'name' => 'interview',
                'menu' => 'dashboard'
            ],
            [
                'name' => 'employee_document_view',
                'menu' => 'dashboard'
            ],
            [
                'name' => 'employee_wish_view',
                'menu' => 'dashboard'
            ],
            [
                'name' => 'joining_candidate',
                'menu' => 'dashboard'
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                [
                    'name'          => $permission['name'],
                ],
                [
                    'menu'          => $permission['menu'],
                    'guard_name'    => 'api',
                ]
            );
        }
    }
}
