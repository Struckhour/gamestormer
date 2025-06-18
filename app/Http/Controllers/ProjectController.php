<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $projects = Project::where('created_by', $userId)
            ->orWhereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function edit(Project $project)
    {
        $project->load('users'); // Eager load the 'users' relationship here

        $isCreator = ($project->created_by === Auth::id());
        $isAssigned = $project->users->contains(Auth::id()); // Check if the current user is in the assigned users collection

        if (! $isCreator && ! $isAssigned) {
            abort(403, 'Unauthorized action.');
        }

        return view('projects.edit', compact('project'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_image' => 'nullable|image|max:2048|mimes:jpeg,png,gif,svg', // 2MB max, common image types
        ]);

        $validatedData['created_by'] = Auth::id();
        $project = Project::create($validatedData);

        if ($request->hasFile('media')) {
            $uploadedFile = $request->file('media');

            // Store the file in the 'public' disk, under 'uploads/projects/media' directory
            // This will put files in storage/app/public/uploads/projects/media
            $path = $uploadedFile->store('uploads/projects/media', 'public');

            // Save file metadata to the 'media' table
            $media = new Media;
            $media->project_id = $project->id; // Link to the newly created project
            $media->file_name = basename($path); // Get just the file name from the path
            $media->original_name = $uploadedFile->getClientOriginalName();
            $media->mime_type = $uploadedFile->getMimeType();
            $media->path = $path; // Store the full relative path
            $media->size = $uploadedFile->getSize();
            $media->save();
        }

        // 4. Redirect or return response
        return redirect()->route('projects.show', $project)->with('success', 'Project created successfully!');
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Update basic fields
        $project->name = $validated['name'];
        $project->description = $validated['description'] ?? null;

        // Handle media upload
        if ($request->hasFile('media')) {
            $uploadedFile = $request->file('media');

            $path = $uploadedFile->store('uploads/projects/media', 'public');

            // Save file metadata to the 'media' table
            $media = new Media;
            $media->project_id = $project->id; // Link to the newly created project
            $media->file_name = basename($path); // Get just the file name from the path
            $media->original_name = $uploadedFile->getClientOriginalName();
            $media->mime_type = $uploadedFile->getMimeType();
            $media->path = $path; // Store the full relative path
            $media->size = $uploadedFile->getSize();
            $media->save();
        }

        $project->save();

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project) // Laravel automatically injects the Project model here
    {

        $project->load('users'); // Eager load the 'users' relationship here

        $isCreator = ($project->created_by === Auth::id());
        $isAssigned = $project->users->contains(Auth::id()); // Check if the current user is in the assigned users collection

        if (! $isCreator && ! $isAssigned) {
            abort(403, 'Unauthorized action.');
        }

        return view('projects.show', compact('project'));
    }

    /**
     * Invite a user to a specific project.
     */
    public function inviteUser(Request $request, Project $project)
    {
        // 1. Authorization: Ensure only the project creator can invite
        if ($project->created_by !== Auth::id()) {
            return back()->with('invite_error', 'You are not authorized to invite users to this project.');
            // Alternatively, abort(403, 'Unauthorized action.');
        }

        // 2. Validate the incoming request (email)
        $request->validate([
            'email' => 'required|email|exists:users,email', // 'exists:users,email' checks if email is in your users table
        ], [
            'email.exists' => 'No user found with this email address.',
        ]);

        // 3. Find the user to invite
        $userToInvite = User::where('email', $request->email)->first();

        // Should not be null due to 'exists' validation, but good practice to check
        if (! $userToInvite) {
            return back()->with('invite_error', 'User not found.');
        }

        // 4. Prevent inviting the creator themselves (optional, but often desired)
        if ($userToInvite->id === Auth::id()) {
            return back()->with('invite_error', 'You are already the project creator and assigned.');
        }

        // 5. Check if user is already assigned to the project
        if ($project->users->contains($userToInvite->id)) {
            return back()->with('invite_error', 'This user is already assigned to the project.');
        }

        // 6. Attach the user to the project using the many-to-many relationship
        $project->users()->attach($userToInvite->id);

        return back()->with('invite_success', 'User '.$userToInvite->name.' has been successfully invited!');
    }
}
