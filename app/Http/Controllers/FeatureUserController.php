<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureUserController extends Controller
{
    public function assign(Request $request)
    {
        $validated = $request->validate([
            'feature_id' => 'required|exists:features,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $feature = Feature::with('project')->findOrFail($validated['feature_id']);
        $project = $feature->project;

        // Build list of allowed users: creator + collaborators
        $allowedUserIds = $project->users->pluck('id')->toArray();
        $allowedUserIds[] = $project->created_by;

        // Filter submitted user_ids to only include allowed ones
        $safeUserIds = collect($validated['user_ids'] ?? [])
            ->filter(fn ($id) => in_array($id, $allowedUserIds))
            ->toArray();

        if (! empty($safeUserIds)) {
            foreach ($safeUserIds as $userId) {
                $feature->users()->syncWithoutDetaching([$userId]);
            }
        }

        return back();
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'feature_id' => 'required|exists:features,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $feature = Feature::with('project')->findOrFail($validated['feature_id']);
        $project = $feature->project;

        // Make sure the user is allowed to remove
        $allowedUserIds = $project->users->pluck('id')->toArray();
        $allowedUserIds[] = $project->created_by;

        if (! in_array(auth()->id(), $allowedUserIds)) {
            abort(403);
        }

        $feature->users()->detach($validated['user_id']);

        return back();
    }
}
