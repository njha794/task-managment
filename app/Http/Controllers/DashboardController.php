<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->hasRole('Super Admin')) {
            return $this->adminDashboard();
        }
        if ($user->hasRole('Project Manager')) {
            return $this->projectManagerDashboard($user);
        }
        if ($user->hasRole('Manager') || $user->hasRole('HR')) {
            return $this->managerDashboard($user);
        }
        if ($user->hasRole('Team Lead')) {
            return $this->teamLeadDashboard($user);
        }

        return $this->userDashboard($request, $user);
    }

    private function adminDashboard(): View
    {
        $stats = [
            'projects' => Project::count(),
            'milestones' => Milestone::count(),
            'tasks' => Task::count(),
            'users' => User::count(),
        ];
        $projects = Project::with(['creator', 'milestones'])->latest()->paginate(10);
        $users = User::with('roles')->latest()->paginate(10);

        return view('dashboard.admin', compact('stats', 'projects', 'users'));
    }

    private function projectManagerDashboard(User $user): View
    {
        $projects = $user->projectsCreated()
            ->withCount('milestones')
            ->with(['milestones' => fn ($q) => $q->withCount('tasks')])
            ->latest()
            ->paginate(10);

        return view('dashboard.project-manager', compact('projects'));
    }

    private function managerDashboard(User $user): View
    {
        $projects = $user->assignedProjects()
            ->with(['milestones.tasks', 'creator'])
            ->withCount('milestones')
            ->latest()
            ->paginate(10);

        return view('dashboard.manager', compact('projects'));
    }

    private function teamLeadDashboard(User $user): View
    {
        $tasks = Task::where('assigned_to', $user->id)
            ->orWhereHas('milestone.project', fn ($q) => $q->whereHas('members', fn ($m) => $m->where('user_id', $user->id)))
            ->with(['milestone.project', 'assignee', 'creator'])
            ->latest()
            ->paginate(15);

        $teamTasks = Task::whereHas('milestone.project.members', fn ($q) => $q->where('user_id', $user->id))
            ->with(['milestone.project', 'assignee'])
            ->latest()
            ->get();

        return view('dashboard.team-lead', compact('tasks', 'teamTasks'));
    }

    private function userDashboard(Request $request, User $user): View
    {
        $tab = $request->get('tab', 'pending'); // all, pending, completed
        $search = $request->get('search');
        $priority = $request->get('priority');
        $projectId = $request->get('project');

        $query = $user->assignedTasks()
            ->with(['milestone.project', 'creator']);

        if ($tab === 'pending') {
            $query->whereIn('status', ['pending', 'in_progress']);
        } elseif ($tab === 'completed') {
            $query->where('status', 'completed');
        }
        // 'all' = no status filter

        if ($search && trim($search) !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . trim($search) . '%')
                    ->orWhere('description', 'like', '%' . trim($search) . '%');
            });
        }

        if ($priority && in_array($priority, Task::PRIORITIES, true)) {
            $query->where('priority', $priority);
        }

        if ($projectId) {
            $query->whereHas('milestone', fn ($q) => $q->where('project_id', $projectId));
        }

        $tasks = $query->latest()->paginate(15)->withQueryString();

        $projectsForFilter = Project::whereHas('milestones.tasks', fn ($q) => $q->where('assigned_to', $user->id))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('dashboard.user', compact('tasks', 'tab', 'search', 'priority', 'projectId', 'projectsForFilter'));
    }
}
