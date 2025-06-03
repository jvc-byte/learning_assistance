<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

Route::resource('users', RoleController::class)
            ->only(["create", "store"])
            ->middleware('permission:users.create');

Route::resource('users', RoleController::class)
            ->only(["edit", "update"])
            ->middleware('permission:users.edit');

Route::resource('users', RoleController::class)
            ->only(["destroy"])
            ->middleware('permission:users.delete');



