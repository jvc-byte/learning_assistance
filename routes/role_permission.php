<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

Route::resource('roles', RoleController::class)
            ->only(["create", "store"])
            ->middleware('permission:roles.create');

Route::resource('roles', RoleController::class)
            ->only(["edit", "update"])
            ->middleware('permission:roles.edit');

Route::resource('roles', RoleController::class)
            ->only(["destroy"])
            ->middleware('permission:roles.delete');



