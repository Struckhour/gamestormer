<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Feature; // To access the parent project
use App\Models\Project; // For dropdowns
use App\Models\Subdepartment; // For dropdowns
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// For unique validation

class FeatureController extends Controller
{
    /**
     * Display a listing of the features for a specific project.
     */
    public function index(Project $project)
    {
        // Authorization: Ensure current user can view this project's features
        // Same logic as ProjectController@show: creator or assigned member
        $project->load('users');
        $isCreator = ($project->created_by === Auth::id());
        $isAssigned = $project->users->contains(Auth::id());

        if (! $isCreator && ! $isAssigned) {
            abort(403, 'Unauthorized action. You do not have access to this project\'s features.');
        }

        // Eager load related models for display
        $features = $project->features()->with(['department', 'subdepartment'])->orderBy('sort_order')->get();

        return view('features.index', compact('project', 'features'));
    }

    /**
     * Show the form for creating a new feature for a specific project.
     */
    public function create(Project $project)
    {
        // Authorization: Only project creator or assigned members can add features
        $project->load('users');
        $isCreator = ($project->created_by === Auth::id());
        $isAssigned = $project->users->contains(Auth::id());

        if (! $isCreator && ! $isAssigned) {
            abort(403, 'Unauthorized action. You do not have permission to add features to this project.');
        }

        $departments = Department::all();
        // For simplicity, initially load all subdepartments.
        // For a dynamic dropdown, you'd implement AJAX/JS to filter subdepartments by selected department_id.
        $subdepartments = Subdepartment::all();

        return view('features.create', compact('project', 'departments', 'subdepartments'));
    }

    /**
     * Store a newly created feature in storage for a specific project.
     */
    public function store(Request $request, Project $project)
    {
        // Authorization: Only project creator or assigned members can add features
        $project->load('users');
        $isCreator = ($project->created_by === Auth::id());
        $isAssigned = $project->users->contains(Auth::id());

        if (! $isCreator && ! $isAssigned) {
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

        // Ensure subdepartment_id belongs to the selected department_id (optional but good for data integrity)
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

    /**
     * Display the specified feature.
     */
    public function show(Project $project, Feature $feature)
    {
        // Authorization: Ensure the feature belongs to the project and current user can view project
        if ($feature->project_id !== $project->id) {
            abort(404); // Feature does not belong to this project
        }

        $project->load('users');
        $isCreator = ($project->created_by === Auth::id());
        $isAssigned = $project->users->contains(Auth::id());

        if (! $isCreator && ! $isAssigned) {
            abort(403, 'Unauthorized action. You do not have access to this project\'s features.');
        }

        // Eager load related models for display
        $feature->load(['department', 'subdepartment']);

        return view('features.show', compact('project', 'feature'));
    }

    /**
     * Show the form for editing the specified feature for a specific project.
     */
    public function edit(Project $project, Feature $feature)
    {
        // Authorization: Ensure feature belongs to project and user can edit
        if ($feature->project_id !== $project->id) {
            abort(404);
        }

        $project->load('users');
        $isCreator = ($project->created_by === Auth::id());
        $isAssigned = $project->users->contains(Auth::id());

        if (! $isCreator && ! $isAssigned) {
            abort(403, 'Unauthorized action. You do not have permission to edit features for this project.');
        }

        $departments = Department::all();
        $subdepartments = Subdepartment::where('department_id', $feature->department_id)->get(); // Filter subdepartments based on current feature's department

        return view('features.edit', compact('project', 'feature', 'departments', 'subdepartments'));
    }

    /**
     * Update the specified feature in storage for a specific project.
     */
    public function update(Request $request, Project $project, Feature $feature)
    {
        // Authorization: Ensure feature belongs to project and user can edit
        if ($feature->project_id !== $project->id) {
            abort(404);
        }

        $project->load('users');
        $isCreator = ($project->created_by === Auth::id());
        $isAssigned = $project->users->contains(Auth::id());

        if (! $isCreator && ! $isAssigned) {
            abort(403, 'Unauthorized action. You do not have permission to update features for this project.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'time_allotted' => 'nullable|integer|min:0',
            'department_id' => 'nullable|exists:departments,id',
            'subdepartment_id' => 'nullable|exists:subdepartments,id',
            'sort_order' => 'nullable|integer|min:0',
            'progress' => 'nullable|string',
            'content' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        // Ensure subdepartment_id belongs to the selected department_id (optional but good for data integrity)
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

    /**
     * Remove the specified feature from storage for a specific project.
     */
    public function destroy(Project $project, Feature $feature)
    {
        // Authorization: Ensure feature belongs to project and user can delete
        if ($feature->project_id !== $project->id) {
            abort(404);
        }

        $project->load('users');
        $isCreator = ($project->created_by === Auth::id());
        $isAssigned = $project->users->contains(Auth::id());

        if (! $isCreator && ! $isAssigned) {
            abort(403, 'Unauthorized action. You do not have permission to delete features for this project.');
        }

        $feature->delete();

        return redirect()->route('projects.features.index', $project)->with('success', 'Feature deleted successfully!');
    }
}
