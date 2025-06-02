<?php

namespace App\Http\Controllers;

use Faker\Guesser\Name;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('roles/Index', [
            'roles' => Role::with('permissions')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('roles/Create', [
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "permissions" => "required",
        ]);

        $role = Role::create([
            "name" => $request->name,
        ]);

        $role->syncPermissions($request->permissions);
         return to_route('roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('roles/Show', [
            'role' => Role::with('permissions')->find($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('roles/Edit', [
            'permissions' => Permission::all(),
            'role' => Role::with('permissions')->find($id),
        ]);
        return to_route('roles.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required",
            "permissions" => "required",
        ]);

        $role = Role::find($id);
        $role->update([
            "name" => $request->name,
        ]);
        $role->syncPermissions($request->permissions);
        return to_route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        $role->delete();
        return to_route('roles.index');
    }
}
