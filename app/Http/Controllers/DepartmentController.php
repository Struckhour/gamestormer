<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

// Already imported in your existing code, but ensure it's there

class DepartmentController extends Controller
{
    /**
     * Display a listing of the departments.
     */
    public function index()
    {
        // The 'admin' middleware ensures only admins can reach this point.
        $departments = Department::all();

        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name', // Name must be unique
            'description' => 'nullable|string',
        ]);

        // Create the new department
        Department::create($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully!');
    }

    /**
     * Show the form for editing the specified department.
     * Laravel's Route Model Binding automatically injects the Department model.
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified department in storage.
     * Laravel's Route Model Binding automatically injects the Department model.
     */
    public function update(Request $request, Department $department)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,'.$department->id, // Allow existing name for this department
            'description' => 'nullable|string',
        ]);

        // Update the department
        $department->update($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully!');
    }

    /**
     * Remove the specified department from storage.
     * Laravel's Route Model Binding automatically injects the Department model.
     */
    public function destroy(Department $department)
    {
        // Delete the department
        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully!');
    }
}
