<?php

namespace App\View\Components;

use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class ProjectFeaturesNav extends Component
{
    public Project $project;

    public array $groupedFeatures; // Structured array of grouped features

    public Collection $unassignedFeatures; // Collection of features with null department/subdepartment

    public ?int $activeFeatureId;

    /**
     * Create a new component instance.
     */
    public function __construct(Project $project, array $groupedFeatures, Collection $unassignedFeatures, ?int $activeFeatureId = null)
    {
        $this->project = $project;
        $this->groupedFeatures = $groupedFeatures;
        $this->unassignedFeatures = $unassignedFeatures;
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
