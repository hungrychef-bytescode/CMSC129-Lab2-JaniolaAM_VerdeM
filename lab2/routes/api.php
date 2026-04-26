use App\Http\Controllers\TaskAPIController;

Route::get('/tasks', [TaskAPIController::class, 'index']);
Route::post('/tasks', [TaskAPIController::class, 'store']);
Route::put('/tasks/{id}', [TaskAPIController::class, 'update']);

Route::post('/tasks/{id}/archive', [TaskAPIController::class, 'archive']);
Route::post('/tasks/{id}/restore', [TaskAPIController::class, 'restore']);
Route::delete('/tasks/{id}', [TaskAPIController::class, 'forceDelete']);