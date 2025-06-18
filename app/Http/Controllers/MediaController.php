<?php

namespace App\Http\Controllers;

use App\Models\Media; // Assuming you have a Media model for your 'media' table
use App\Models\Project;
use Illuminate\Support\Facades\Storage; // For file deletion

// For authorization

class MediaController extends Controller
{
    /**
     * Remove the specified media from storage.
     */
    public function destroy(Media $media, Project $project)
    {
        // 2. Authorization Check: Is the authenticated user a member of this project?
        $currentUserId = Auth::id(); // Get the ID of the currently logged-in user

        // Check if the current user is the project creator
        $isProjectCreator = ($project->creator_id === $currentUserId);

        // Check if the current user is assigned to the project via the pivot table
        // We'll eager load 'users' on the project if it's not guaranteed to be loaded
        // However, $project->users already fetches it if accessed.
        $isProjectMemberViaPivot = $project->users->contains('id', $currentUserId);

        // If the user is neither the creator nor a project member, deny access
        if (! $isProjectCreator && ! $isProjectMemberViaPivot) {
            abort(403, 'Unauthorized action. You are not a member of this project.');
        }

        try {
            // 1. Delete the file from storage
            Storage::disk('public')->delete($media->path); // Assumes 'public' disk and 'path' stores relative path

            // 2. Delete the record from the database
            $media->delete();

            return back()->with('success', 'Media deleted successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error deleting media: '.$e->getMessage(), ['media_id' => $media->id]);

            return back()->with('error', 'Failed to delete media. Please try again.');
        }
    }
}
