<?php

namespace Database\Seeders;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating demo users with roles...');

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'Super Admin',
            ],
            [
                'name' => 'Project Manager One',
                'email' => 'pm@example.com',
                'password' => Hash::make('password'),
                'role' => 'Project Manager',
            ],
            [
                'name' => 'Manager One',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'role' => 'Manager',
            ],
            [
                'name' => 'HR One',
                'email' => 'hr@example.com',
                'password' => Hash::make('password'),
                'role' => 'HR',
            ],
            [
                'name' => 'Team Lead One',
                'email' => 'teamlead@example.com',
                'password' => Hash::make('password'),
                'role' => 'Team Lead',
            ],
            [
                'name' => 'Employee User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role' => 'User',
            ],
        ];

        $createdUsers = [];
        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                $data
            );
            if (! $user->hasRole($role)) {
                $user->assignRole($role);
            }
            $createdUsers[$role] = $user;
        }

        $this->command->info('Creating demo project, milestones, and tasks...');

        $pm = $createdUsers['Project Manager'];
        $manager = $createdUsers['Manager'];
        $teamLead = $createdUsers['Team Lead'];
        $employee = $createdUsers['User'];

        $project = Project::firstOrCreate(
            ['name' => 'Website Redesign'],
            [
                'description' => 'Complete redesign of company website.',
                'created_by' => $pm->id,
            ]
        );

        $project->members()->syncWithoutDetaching([
            $manager->id => ['role' => 'manager'],
            $teamLead->id => ['role' => 'team_lead'],
        ]);

        $milestone1 = Milestone::firstOrCreate(
            [
                'project_id' => $project->id,
                'title' => 'Design Phase',
            ],
            [
                'description' => 'UI/UX design and mockups.',
                'due_date' => now()->addDays(14),
            ]
        );

        $milestone2 = Milestone::firstOrCreate(
            [
                'project_id' => $project->id,
                'title' => 'Development Phase',
            ],
            [
                'description' => 'Frontend and backend development.',
                'due_date' => now()->addDays(30),
            ]
        );

        $task1 = Task::firstOrCreate(
            [
                'milestone_id' => $milestone1->id,
                'title' => 'Create wireframes',
            ],
            [
                'description' => 'Low-fidelity wireframes for main pages.',
                'priority' => 'high',
                'status' => 'in_progress',
                'start_date' => now(),
                'due_date' => now()->addDays(5),
                'assigned_to' => $teamLead->id,
                'created_by' => $manager->id,
            ]
        );

        $task2 = Task::firstOrCreate(
            [
                'milestone_id' => $milestone1->id,
                'title' => 'Design homepage',
            ],
            [
                'description' => 'High-fidelity design for homepage.',
                'priority' => 'medium',
                'status' => 'pending',
                'due_date' => now()->addDays(10),
                'assigned_to' => $employee->id,
                'created_by' => $teamLead->id,
            ]
        );

        $task3 = Task::firstOrCreate(
            [
                'milestone_id' => $milestone2->id,
                'title' => 'Setup backend API',
            ],
            [
                'description' => 'Laravel API setup and auth.',
                'priority' => 'high',
                'status' => 'pending',
                'due_date' => now()->addDays(20),
                'assigned_to' => $employee->id,
                'created_by' => $manager->id,
            ]
        );

        $this->command->info('Demo data created. Login with any user e.g. admin@example.com / password');
    }
}
