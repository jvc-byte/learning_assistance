<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::resource('users', UserController::class)
            ->only(["create", "store"])
            ->middleware('permission:users.create');

Route::resource('users', UserController::class)
            ->only(["edit", "update"])
            ->middleware('permission:users.edit');

Route::resource('users', UserController::class)
            ->only(["destroy"])
            ->middleware('permission:users.delete');



