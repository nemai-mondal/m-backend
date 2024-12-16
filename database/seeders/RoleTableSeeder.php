<?php

namespace Database\Seeders;

// use App\Models\Role;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesData = [
            0 => [
                'name'              =>  'super_admin',
            ],
            1 => [
                'name'              =>  'admin',
            ],
            2 => [
                'name'              =>  'hr',
            ],
            3 => [
                'name'              =>  'employee',
            ],
        ];

        $permissions = Permission::pluck('id');

        $empPermissions = Permission::whereIn('name', [
                                        // 'holiday_view',
                                        'hr_announcement_view',
                                        'motivational_quote_view',
                                        'worklog_create',
                                        'worklog_view',
                                        'timelog_create',
                                        'timelog_view',
                                        'leave_create',
                                        'amendment_view',
                                        'punchinout',
                                        'attendence_and_activity',
                                        // 'quote',
                                        'holiday_and_leave',
                                        'hr_announcement',
                                        'celebrating_events',
                                    ])
                                    ->pluck('id');

        $hrPermissions = Permission::whereIn('name', [
                                        'interview_create',
                                        'interview_update',
                                        'interview_view',
                                        'interview_delete',
                                        'interview_deactivate',
                                        'holiday_create',
                                        'holiday_update',
                                        'holiday_view',
                                        'holiday_delete',
                                        'holiday_deactivate',
                                        'hr_announcement_create',
                                        'hr_announcement_update',
                                        'hr_announcement_view',
                                        'hr_announcement_delete',
                                        'hr_announcement_deactivate',
                                        'leave_ratio_create',
                                        'leave_ratio_update',
                                        'leave_ratio_view',
                                        'leave_ratio_delete',
                                        'leave_ratio_deactivate',
                                        'leave_type_create',
                                        'leave_type_update',
                                        'leave_type_view',
                                        'leave_type_delete',
                                        'leave_type_deactivate',
                                        'motivational_quote_create',
                                        'motivational_quote_update',
                                        'motivational_quote_view',
                                        'motivational_quote_delete',
                                        'motivational_quote_deactivate',
                                        'shift_create',
                                        'shift_update',
                                        'shift_view',
                                        'shift_delete',
                                        'shift_deactivate',
                                        'shift_rule_create',
                                        'shift_rule_update',
                                        'shift_rule_view',
                                        'shift_rule_delete',
                                        'shift_rule_deactivate',
                                        'user_create',
                                        'user_update',
                                        'user_view',
                                        'user_delete',
                                        'user_deactivate',
                                        'worklog_create',
                                        'worklog_update',
                                        'worklog_view',
                                        'worklog_delete',
                                        'worklog_deactivate',
                                        'timelog_create',
                                        'timelog_update',
                                        'timelog_view',
                                        'timelog_delete',
                                        'timelog_deactivate',
                                        'leave_create',
                                        'amendment_create',
                                        'amendment_update',
                                        'amendment_view',
                                        'amendment_delete',
                                        'amendment_deactivate',
                                        'punchinout',
                                        'attendence_and_activity',
                                        'quote',
                                        'holiday_and_leave',
                                        'hr_announcement',
                                        'celebrating_events',
                                        'interview',
                                        'joining_candidate',
                                    ])
                                    ->pluck('id');

        foreach ($rolesData as $roleData) {
            // Create or retrieve the role
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                ['guard_name' => 'api']
            );

            // If the role is 'super admin' or 'admin', assign all permissions
            if ($role->name == 'super_admin' || $role->name == 'admin') {
                $role->syncPermissions($permissions);
            }

            if ($role->name == 'employee') {
                $role->syncPermissions($empPermissions);
            }

            if ($role->name == 'hr') {
                $role->syncPermissions($hrPermissions);
            }
        }
    }
}
