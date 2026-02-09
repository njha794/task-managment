# Project Management System

A role-based project, milestone, and task management system built with **Laravel 12**, **MySQL**, **Laravel Breeze (Blade)**, and **Spatie Laravel Permission**.

## Tech Stack

- **Laravel 12**
- **MySQL**
- **Laravel Breeze** (Blade + Tailwind)
- **Spatie Laravel Permission** (roles & permissions)
- **MVC + Service Layer** architecture

## Setup

1. **Clone and install dependencies**
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

2. **Configure database** (`.env`)
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

3. **Run migrations and seed**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Build frontend (optional; Breeze uses Vite)**
   ```bash
   npm install
   npm run build
   ```

5. **Serve the application**
   ```bash
   php artisan serve
   ```
   Open `http://localhost:8000`.

## Demo Logins

After seeding, you can log in with:

| Role           | Email              | Password  |
|----------------|--------------------|-----------|
| Super Admin    | admin@example.com  | password  |
| Project Manager| pm@example.com     | password  |
| Manager        | manager@example.com| password  |
| HR             | hr@example.com     | password  |
| Team Lead      | teamlead@example.com| password |
| User (Employee)| user@example.com   | password  |

## Roles & Permissions

### Role hierarchy (highest to lowest)

1. **Super Admin** – Full system access: manage users, roles, all projects/tasks, reports.
2. **Project Manager** – Create/edit/delete projects; create milestones; assign managers/team leads; view project progress.
3. **Manager** – Assigned projects/milestones; create and assign tasks; update deadlines; view task status.
4. **HR** – Same as Manager in this app (assign projects, create/assign tasks, view status).
5. **Team Lead** – Tasks assigned to their team; assign tasks to users; update task progress; mark completed.
6. **User (Employee)** – My assigned tasks only; view details; update status (Pending / In Progress / Completed); view deadlines.

### Permissions (examples)

- `create_project`, `edit_project`, `delete_project`, `view_all_projects`
- `create_milestone`, `edit_milestone`, `delete_milestone`
- `create_task`, `edit_task`, `delete_task`, `assign_task`, `update_task_status`
- `view_reports`, `manage_users`, `manage_roles`

Spatie handles role–permission assignment; see `database/seeders/RoleAndPermissionSeeder.php` for the mapping.

## Project flow

1. **Project** is created (by Project Manager or Super Admin).
2. **Milestones** are added under the project.
3. **Tasks** are created under milestones.
4. Tasks are **assigned** to users (Team Lead or User).
5. **Task status** updates (pending → in_progress → completed) drive milestone and project **progress** (percentage based on completed tasks).

## Dashboards

- **Landing (guest)** – System overview, Login, Register.
- **Super Admin** – Stats (projects, milestones, tasks, users), role management, create/edit/delete projects and users, view all.
- **Project Manager** – My projects, create project, milestones, assign members, project progress.
- **Manager / HR** – Assigned projects and milestones, create/assign tasks, view/update status and deadlines.
- **Team Lead** – Team tasks, assign tasks, update progress, mark completed.
- **User** – My tasks, task details, update status, view deadlines.

## Architecture

- **Controllers** – Thin; delegate to services and authorize with policies.
- **Services** – `ProjectService`, `MilestoneService`, `TaskService` for create/update/delete and assignments.
- **Form Requests** – Validation and authorization for store/update (e.g. `StoreProjectRequest`, `UpdateTaskRequest`, `UpdateTaskStatusRequest`).
- **Policies** – `ProjectPolicy`, `MilestonePolicy`, `TaskPolicy` for view/create/update/delete and status updates.
- **Models** – Eloquent relationships: Project → milestones, Milestone → tasks, Task → assignee/creator, User → roles/permissions and assigned projects/tasks.
- **Database** – `users`, `projects`, `milestones`, `tasks`, `project_user` (pivot), plus Spatie tables (`roles`, `permissions`, `model_has_roles`, etc.).

## Folder structure (high level)

```
app/
  Http/Controllers/     # Dashboard, Project, Milestone, Task, Admin\User
  Http/Requests/       # Store/Update form requests
  Models/              # User, Project, Milestone, Task
  Policies/            # Project, Milestone, Task
  Services/            # Project, Milestone, Task
database/
  migrations/          # users, permission tables, projects, milestones, tasks, project_user
  seeders/             # RoleAndPermissionSeeder, DemoDataSeeder
resources/views/
  dashboard/           # admin, project-manager, manager, team-lead, user
  projects/            # index, create, show, edit, members
  milestones/          # create, edit
  tasks/               # create, show, edit
  admin/users/         # index, edit
```

## License

MIT.
