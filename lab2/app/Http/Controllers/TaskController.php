<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskList;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with('list');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('task', 'like', "%{$request->search}%")
                  ->orWhere('priority', 'like', "%{$request->search}%");
            });
        }


        if ($request->list_id) {
            $query->where('list_id', $request->list_id);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->priority) {
                $query->where('priority', $request->priority);
            }

            if ($request->boolean('deleted')) {
                $query->onlyTrashed();
            }

            $allowedSorts = ['created_at', 'priority', 'due_date'];

            if ($request->sort && in_array($request->sort, $allowedSorts)) {
                $query->orderBy($request->sort, $request->order ?? 'asc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $tasks = $query->paginate(10)->withQueryString();

        $lists = TaskList::all();

        return view('tasks.index', compact('tasks', 'lists'));
    }

    public function create()
    {
        $lists = TaskList::all();
        return view('tasks.create', compact('lists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            'due_date' => 'nullable|date',
            'list_id' => 'required|exists:task_lists,id'
        ]);

        Task::create($validated + ['status' => 0,  'timestamp' => now(),]);

        return redirect()->route('tasks.index')->with('success', 'Task created!');
    }

    public function edit(Task $task)
    {
        $lists = TaskList::all();
        return view('tasks.edit', compact('task', 'lists'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'task' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            'due_date' => 'nullable|date',
            'status' => 'required|integer|min:0|max:2'
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index', ['list_id' => $task->list_id])->with('success', 'Task updated!');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update(['status' => $request->status]);
        return back()->with('success', 'Task status updated!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', 'Task archived!');
    }

    public function restore($id)
    {
        Task::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Task restored!');
    }

    public function forceDelete($id)
    {
        Task::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Task permanently deleted!');
    }
}