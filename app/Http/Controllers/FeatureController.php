<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Feature;
use App\Models\Project;
use App\Models\Subdepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureController extends Controller
{
    // Helper method to get common data for sidebar
    protected function getCommonFeatureData(Project $project, ?Feature $activeFeature = null)
    {
        $project->load('users'); // Ensure users are loaded for auth checks

        // Get all features for the current project, only title and id for sidebar
        $allProjectFeatures = $project->features()->select('id', 'title')->orderBy('sort_order')->get();

        return [
            'project' => $project,
            'allProjectFeatures' => $allProjectFeatures,
            'activeFeatureId' => $activeFeature ? $activeFeature->id : null,
            'isCreator' => ($project->created_by === Auth::id()),
            'isAssigned' => $project->users->contains(Auth::id()),
        ];
    }

    public function index(Project $project)
    {
        $data = $this->getCommonFeatureData($project);
        if (! $data['isCreator'] && ! $data['isAssigned']) {
            abort(403, 'Unauthorized action. You do not have access to this project\'s features.');
        }

        $features = $project->features()->with(['department', 'subdepartment'])->orderBy('sort_order')->get();

        return view('features.index', array_merge($data, compact('features')));
    }

    public function create(Project $project)
    {
        $data = $this->getCommonFeatureData($project);
        if (! $data['isCreator'] && ! $data['isAssigned']) {
            abort(403, 'Unauthorized action. You do not have permission to add features to this project.');
        }

        $departments = Department::all();
        $subdepartments = Subdepartment::all(); // Or dynamically load with JS later

        return view('features.create', array_merge($data, compact('departments', 'subdepartments')));
    }

    public function store(Request $request, Project $project)
    {
        $data = $this->getCommonFeatureData($project); // Just to get auth flags
        if (! $data['isCreator'] && ! $data['isAssigned']) {
            abort(403, 'Unauthorized action. You do not have permission to add features to this project.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'time_allotted' => 'required|integer|min:0',
            'department_id' => 'required|exists:departments,id',
            'subdepartment_id' => 'nullable|exists:subdepartments,id',
            'sort_order' => 'nullable|integer|min:0',
            'progress' => 'nullable|string',
            'content' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        if ($request->filled('subdepartment_id')) {
            $subdepartment = Subdepartment::where('id', $request->subdepartment_id)
                ->where('department_id', $request->department_id)
                ->first();
            if (! $subdepartment) {
                return back()->withInput()->withErrors(['subdepartment_id' => 'The selected subdepartment does not belong to the chosen department.']);
            }
        }

        $project->features()->create($request->all());

        return redirect()->route('projects.features.index', $project)->with('success', 'Feature created successfully!');
    }

    public function show(Project $project, Feature $feature)
    {
        $data = $this->getCommonFeatureData($project, $feature); // Pass $feature for active ID
        if ($feature->project_id !== $project->id) {
            abort(404);
        }
        if (! $data['isCreator'] && ! $data['isAssigned']) {
            abort(403, 'Unauthorized action. You do not have access to this project\'s features.');
        }

        $feature->load(['department', 'subdepartment', 'comments.creator']);

        return view('features.show', array_merge($data, compact('feature')));
    }

    public function edit(Project $project, Feature $feature)
    {
        $data = $this->getCommonFeatureData($project, $feature); // Pass $feature for active ID
        if ($feature->project_id !== $project->id) {
            abort(404);
        }
        if (! $data['isCreator'] && ! $data['isAssigned']) {
            abort(403, 'Unauthorized action. You do not have permission to edit features for this project.');
        }

        $departments = Department::all();
        $subdepartments = Subdepartment::where('department_id', $feature->department_id)->get();

        return view('features.edit', array_merge($data, compact('feature', 'departments', 'subdepartments')));
    }

    public function update(Request $request, Project $project, Feature $feature)
    {
        $data = $this->getCommonFeatureData($project); // Just to get auth flags
        if ($feature->project_id !== $project->id) {
            abort(404);
        }
        if (! $data['isCreator'] && ! $data['isAssigned']) {
            abort(403, 'Unauthorized action. You do not have permission to update features for this project.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'time_allotted' => 'required|integer|min:0',
            'department_id' => 'required|exists:departments,id',
            'subdepartment_id' => 'nullable|exists:subdepartments,id',
            'sort_order' => 'nullable|integer|min:0',
            'progress' => 'nullable|string',
            'content' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        if ($request->filled('subdepartment_id')) {
            $subdepartment = Subdepartment::where('id', $request->subdepartment_id)
                ->where('department_id', $request->department_id)
                ->first();
            if (! $subdepartment) {
                return back()->withInput()->withErrors(['subdepartment_id' => 'The selected subdepartment does not belong to the chosen department.']);
            }
        }

        $feature->update($request->all());

        return redirect()->route('projects.features.index', $project)->with('success', 'Feature updated successfully!');
    }

    public function destroy(Project $project, Feature $feature)
    {
        $data = $this->getCommonFeatureData($project); // Just to get auth flags
        if ($feature->project_id !== $project->id) {
            abort(404);
        }
        if (! $data['isCreator'] && ! $data['isAssigned']) {
            abort(403, 'Unauthorized action. You do not have permission to delete features for this project.');
        }

        $feature->delete();

        return redirect()->route('projects.features.index', $project)->with('success', 'Feature deleted successfully!');
    }
}
