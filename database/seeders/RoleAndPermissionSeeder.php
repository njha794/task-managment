<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public const PERMISSIONS = [
        'create_project',
        'edit_project',
        'delete_project',
        'view_all_projects',
        'create_milestone',
        'edit_milestone',
        'delete_milestone',
        'create_task',
        'edit_task',
        'delete_task',
        'assign_task',
        'update_task_status',
        'view_reports',
        'manage_users',
        'manage_roles',
    ];

    public const ROLES = [
        'Super Admin' => ['*'], // all permissions
        'Project Manager' => [
            'create_project', 'edit_project', 'delete_project', 'view_all_projects',
            'create_milestone', 'edit_milestone', 'delete_milestone',
            'create_task', 'edit_task', 'delete_task', 'assign_task', 'update_task_status',
            'view_reports',
        ],
        'Manager' => [
            'create_milestone', 'edit_milestone', 'create_task', 'edit_task', 'assign_task',
            'update_task_status', 'view_reports',
        ],
        'HR' => [
            'create_milestone', 'edit_milestone', 'create_task', 'edit_task', 'assign_task',
            'update_task_status', 'view_reports',
        ],
        'Team Lead' => [
            'create_task', 'edit_task', 'assign_task', 'update_task_status',
        ],
        'User' => [
            'update_task_status',
        ],
    ];

    public function run(): void
    {
        $this->command->info('Creating permissions...');
        foreach (self::PERMISSIONS as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $this->command->info('Creating roles and assigning permissions...');
        foreach (self::ROLES as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            if (in_array('*', $permissions, true)) {
                $role->givePermissionTo(Permission::all());
            } else {
                $role->syncPermissions($permissions);
            }
        }
    }
}
