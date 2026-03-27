<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;

//homepage
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

//resource routes (CRUD)
// Route::resource('tasks', TaskController::class);
// Route::resource('lists', TaskListController::class);

// //others
// Route::patch('tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
// Route::delete('tasks/{id}/force', [TaskController::class, 'forceDelete'])->name('tasks.forceDelete');
Route::patch('tasks/{id}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');


Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

Route::patch('tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
Route::delete('tasks/{id}/force', [TaskController::class, 'forceDelete'])->name('tasks.forceDelete');

Route::post('lists', [TaskListController::class, 'store'])->name('lists.store');
Route::delete('lists/{list}', [TaskListController::class, 'destroy'])->name('lists.destroy');