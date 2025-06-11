<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Feature;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Project $project, Feature $feature)
    {
        // Authorization: Ensure user has access to this project/feature
        // Re-use logic from FeatureController@show to ensure access
        $project->load('users');
        $isCreator = ($project->created_by === Auth::id());
        $isAssigned = $project->users->contains(Auth::id());

        if (! $isCreator && ! $isAssigned) {
            abort(403, 'Unauthorized action. You do not have permission to comment on this feature.');
        }

        // Validate the comment text
        $request->validate([
            'comment' => 'required|string|max:1000', // Limit comment length
        ]);

        // Create the comment
        $feature->comments()->create([
            'comment' => $request->comment,
            'created_by' => Auth::id(), // Set the creator to the currently authenticated user
        ]);

        return redirect()->route('projects.features.show', [$project, $feature])
            ->with('success', 'Comment added successfully!');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Project $project, Feature $feature, Comment $comment)
    {
        // Authorization:
        // 1. Ensure the comment belongs to the specified feature and project
        if ($comment->feature_id !== $feature->id || $feature->project_id !== $project->id) {
            abort(404); // Comment/Feature/Project mismatch
        }

        // 2. Only the comment creator OR an admin can delete the comment
        if ($comment->created_by !== Auth::id() && ! Auth::user()->is_admin) {
            abort(403, 'Unauthorized action. You do not have permission to delete this comment.');
        }

        $comment->delete();

        return redirect()->route('projects.features.show', [$project, $feature])
            ->with('success', 'Comment deleted successfully!');
    }
}
