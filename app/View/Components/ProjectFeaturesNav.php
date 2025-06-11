<?php

namespace App\View\Components;

use App\Models\Project; // Make sure to import Project
use Illuminate\Database\Eloquent\Collection; // For type hinting
use Illuminate\View\Component;
use Illuminate\View\View;

class ProjectFeaturesNav extends Component
{
    public Project $project;

    public Collection $features; // All features for the project (id, title)

    public ?int $activeFeatureId; // ID of the feature currently being viewed/edited

    /**
     * Create a new component instance.
     */
    public function __construct(Project $project, Collection $features, ?int $activeFeatureId = null)
    {
        $this->project = $project;
        $this->features = $features;
        $this->activeFeatureId = $activeFeatureId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.project-features-nav');
    }
}
