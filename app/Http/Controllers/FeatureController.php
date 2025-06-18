<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Feature;
use App\Models\Project;
use App\Models\Status;
use App\Models\Subdepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureController extends Controller
{
    // Helper method to get common data for sidebar
    protected function getCommonFeatureData(Project $project, ?Feature $activeFeature = null)
    {
        $project->load('users'); // Ensure users are loaded for auth checks

        // Get all features for the current project, eager-loaded with department and subdepartment
        // and grouped for the navigation tree.
        $features = $project->features()
            ->with(['department', 'subdepartment'])
            ->orderBy('sort_order')
            ->get();

        $groupedFeatures = [];
        $unassignedFeatures = collect();

        // Group features by Department and Subdepartment
        foreach ($features as $feature) {
            if ($feature->department && $feature->subdepartment) {
                // Ensure department exists in groupedFeatures
                if (! isset($groupedFeatures[$feature->department->id])) {
                    $groupedFeatures[$feature->department->id] = [
                        'name' => $feature->department->name,
                        'subdepartments' => [],
                    ];
                }
                // Ensure subdepartment exists within the department
                if (! isset($groupedFeatures[$feature->department->id]['subdepartments'][$feature->subdepartment->id])) {
                    $groupedFeatures[$feature->department->id]['subdepartments'][$feature->subdepartment->id] = [
                        'name' => $feature->subdepartment->name,
                        'features' => [],
                    ];
                }
                $groupedFeatures[$feature->department->id]['subdepartments'][$feature->subdepartment->id]['features'][] = $feature;
            } elseif ($feature->department) {
                // Features with a department but no subdepartment
                if (! isset($groupedFeatures[$feature->department->id])) {
                    $groupedFeatures[$feature->department->id] = [
                        'name' => $feature->department->name,
                        'subdepartments' => [], // Still include subdepartments key even if empty for this group
                        'features_no_subdepartment' => [], // For features directly under department
                    ];
                }
                $groupedFeatures[$feature->department->id]['features_no_subdepartment'][] = $feature;
            } else {
                // Features with no department or subdepartment (null)
                $unassignedFeatures->push($feature);
            }
        }

        // Sort departments, subdepartments, and features within groups if necessary (optional)
        // For simplicity, we are relying on the order of iteration here.
        // If specific sorting is needed, you can implement it here.
        // For example, sorting departments by name:
        // uksort($groupedFeatures, fn($a, $b) => strcmp($groupedFeatures[$a]['name'], $groupedFeatures[$b]['name']));

        return [
            'project' => $project,
            'groupedFeatures' => $groupedFeatures, // The newly structured data
            'unassignedFeatures' => $unassignedFeatures, // Features with null department/subdepartment
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

        $features = $project->features()->with(['department', 'subdepartment'])->orderByRaw('deadline IS NULL, deadline ASC')->get();

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
        $statuses = Status::all();

        return view('features.create', array_merge($data, compact('departments', 'subdepartments', 'statuses')));
    }

    public function store(Request $request, Project $project)
    {
        $data = $this->getCommonFeatureData($project); // Just to get auth flags
        if (! $data['isCreator'] && ! $data['isAssigned']) {
            abort(403, 'Unauthorized action. You do not have permission to add features to this project.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'time_allotted' => 'nullable|integer|min:0',
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

        // Get users who are on the project + project creator
        $projectUsers = $project->users; // from pivot table
        $creator = $project->creator;    // assuming this is defined as a relation

        // Merge into one collection and remove duplicates
        $allProjectMembers = $projectUsers->push($creator)->unique('id');

        // Get the IDs of users already assigned to THIS specific feature
        $assignedUserIds = $feature->users->pluck('id')->toArray();

        // Filter the $allProjectMembers to exclude those who are already assigned to this feature
        // This is the collection you'll use for your "Assign a team member" dropdown
        $availableUsers = $allProjectMembers->filter(function ($user) use ($assignedUserIds) {
            return ! in_array($user->id, $assignedUserIds);
        });

        $statuses = Status::all();
        $feature->load(['department', 'subdepartment', 'comments.creator']);

        return view('features.show', array_merge($data, compact('feature', 'availableUsers', 'statuses')));
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
        $statuses = Status::all();

        return view('features.edit', array_merge($data, compact('feature', 'departments', 'subdepartments', 'statuses')));
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

    public function assignStatus(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'feature_id' => 'required|exists:features,id',
            'status_id' => 'required|exists:statuses,id',
        ]);

        // Retrieve the feature
        $feature = Feature::findOrFail($validated['feature_id']);

        // Assign the new status
        $feature->status_id = $validated['status_id'];
        $feature->save();

        // Optionally, redirect back with a success message
        return back()->with('success', 'Progress status updated.');
    }
}
