<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\ChatController;

//homepage
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

Route::post('/chat', [ChatController::class, 'chat']);


//resource routes (CRUD)
// Route::resource('tasks', TaskController::class);
// Route::resource('lists', TaskListController::class);

// //others
// Route::patch('tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
// Route::delete('tasks/{id}/force', [TaskController::class, 'forceDelete'])->name('tasks.forceDelete');
Route::patch('tasks/{id}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');


Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::patch('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

Route::patch('tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
Route::delete('tasks/{id}/force', [TaskController::class, 'forceDelete'])->name('tasks.forceDelete');

Route::post('lists', [TaskListController::class, 'store'])->name('lists.store');
Route::delete('lists/{list}', [TaskListController::class, 'destroy'])->name('lists.destroy');
 

//quick test routes for APIs of ai chatbot

//Groq API models
Route::get('/test-groq-models', function () {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
    ])->get('https://api.groq.com/openai/v1/models');

    return $response->json();
});

//Groq API working
Route::get('/test-groq', function () {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
        'Content-Type'  => 'application/json',
    ])->post('https://api.groq.com/openai/v1/chat/completions', [
        'model' => 'llama-3.3-70b-versatile',
        'messages' => [
            ['role' => 'user', 'content' => 'Say hello']
        ],
    ]);

    return [
        'status' => $response->status(),
        'data' => $response->json()
    ];
});

//GEMINI API Models
Route::get('/test-gemini-models', function () {
    $apiKey = env('GEMINI_API_KEY');

    $response = Http::get(
        "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}"
    );

    return $response->json();
});

//test if gemini is working
Route::get('/test-gemini', function () {

    $apiKey = env('GEMINI_API_KEY');

    $response = \Illuminate\Support\Facades\Http::post(
        "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
        [
            "contents" => [
                [
                    "parts" => [
                        ["text" => "Working Gemini"]
                    ]
                ]
            ]
        ]
    );

    return $response->json();
});