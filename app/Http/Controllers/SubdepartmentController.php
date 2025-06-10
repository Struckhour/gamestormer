<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Subdepartment; // <-- NEW: Import Department model for dropdown
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- NEW: Import Rule for unique validation

class SubdepartmentController extends Controller
{
    /**
     * Display a listing of the subdepartments.
     */
    public function index()
    {
        // Eager load the parent department to display its name
        $subdepartments = Subdepartment::with('department')->get();

        return view('admin.subdepartments.index', compact('subdepartments'));
    }

    /**
     * Show the form for creating a new subdepartment.
     */
    public function create()
    {
        // Get all departments to populate the dropdown
        $departments = Department::all();

        return view('admin.subdepartments.create', compact('departments'));
    }

    /**
     * Store a newly created subdepartment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => [
                'required',
                'string',
                'max:255',
                // Rule to ensure name is unique only for the given department_id
                Rule::unique('subdepartments')->where(function ($query) use ($request) {
                    return $query->where('department_id', $request->department_id);
                }),
            ],
            'description' => 'nullable|string',
        ]);

        Subdepartment::create($request->all());

        return redirect()->route('admin.subdepartments.index')->with('success', 'Subdepartment created successfully!');
    }

    /**
     * Show the form for editing the specified subdepartment.
     */
    public function edit(Subdepartment $subdepartment) // Route Model Binding
    {
        $departments = Department::all(); // Get all departments for the dropdown

        return view('admin.subdepartments.edit', compact('subdepartment', 'departments'));
    }

    /**
     * Update the specified subdepartment in storage.
     */
    public function update(Request $request, Subdepartment $subdepartment) // Route Model Binding
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => [
                'required',
                'string',
                'max:255',
                // Rule to ensure name is unique for the given department_id, excluding current subdepartment
                Rule::unique('subdepartments')->where(function ($query) use ($request) {
                    return $query->where('department_id', $request->department_id);
                })->ignore($subdepartment->id),
            ],
            'description' => 'nullable|string',
        ]);

        $subdepartment->update($request->all());

        return redirect()->route('admin.subdepartments.index')->with('success', 'Subdepartment updated successfully!');
    }

    /**
     * Remove the specified subdepartment from storage.
     */
    public function destroy(Subdepartment $subdepartment) // Route Model Binding
    {
        $subdepartment->delete();

        return redirect()->route('admin.subdepartments.index')->with('success', 'Subdepartment deleted successfully!');
    }
}
