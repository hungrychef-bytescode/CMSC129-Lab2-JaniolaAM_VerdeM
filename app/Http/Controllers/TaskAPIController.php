<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskAPIController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->boolean('archived')) {
            $query->onlyTrashed();
        }

        if ($request->has('list_id')) {
            $query->where('list_id', $request->list_id);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('due_today')) {
            $query->whereDate('due_date', now());
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $task = Task::create($request->all());
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());

        return response()->json($task);
    }

    public function archive($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'archived']);
    }

    public function restore($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $task->restore();

        return response()->json(['message' => 'restored']);
    }

    public function forceDelete($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->forceDelete();

        return response()->json(['message' => 'deleted']);
    }
}