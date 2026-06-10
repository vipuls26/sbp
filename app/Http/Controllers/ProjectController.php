<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\Project\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct(private ProjectService $projectService) {}

    public function index()
    {
        $user = Auth::user();
        $projects = $this->projectService->list($user->id);
        $limit = $user->projectLimit();
        $count = $projects->count();

        return view('user.projects.index', compact('projects', 'limit', 'count'));
    }

    public function create()
    {
        return view('user.projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:active,completed,on_hold'],
            'progress'    => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $result = $this->projectService->create(Auth::user(), $data);

        if (! $result['success']) {
            return redirect()->route('projects.create')->with('error', $result['message']);
        }

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        abort_if($project->user_id !== Auth::id(), 403);

        return view('user.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        abort_if($project->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:active,completed,on_hold'],
            'progress'    => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $this->projectService->update($project->id, $data);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        abort_if($project->user_id !== Auth::id(), 403);

        $this->projectService->delete($project->id, Auth::id());

        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
