<?php

namespace App\Http\Controllers;

class MyTasksController extends Controller
{
    public function index()
    {

        // Eager load project to group by it
        $features = auth()->user()
            ->features()
            ->with('project')
            ->orderBy('deadline') // Order before grouping
            ->get();

        $grouped = $features->groupBy(function ($feature) {
            return $feature->project->id;
        });

        return view('my-tasks.index', compact('grouped'));
    }
}
