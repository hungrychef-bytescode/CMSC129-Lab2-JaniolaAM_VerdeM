@extends('layouts.app')

@section('content')

@if(!request('list_id'))
    <div class="empty-state" style="height: 60vh;">
        <span>📋</span>
        <p class="text-slate-400 text-sm">Select a list from the sidebar to manage your tasks.</p>
    </div>
@else

@php
    $listId = request('list_id');
    $search = request('search');
    $sort = in_array(request('sort'), ['created_at', 'due_date', 'priority']) ? request('sort') : 'created_at';
    $order = request('order', 'asc') === 'desc' ? 'desc' : 'asc';

    $baseQuery = fn() => \App\Models\Task::where('list_id', $listId)
        ->when($search, fn($q) => $q->where('task', 'like', "%{$search}%"));

    $activeTasks = $baseQuery()
        ->where('status', '!=', 2)
        ->whereNull('deleted_at')
        ->orderBy($sort, $order)
        ->get();

    $completedTasks = $baseQuery()
        ->where('status', 2)
        ->whereNull('deleted_at')
        ->orderBy($sort, $order)
        ->get();

    $archivedTasks = \App\Models\Task::where('list_id', $listId)->onlyTrashed()->get();

    $total = \App\Models\Task::where('list_id', $listId)->whereNull('deleted_at')->count();
    $completedCount = \App\Models\Task::where('list_id', $listId)->where('status', 2)->whereNull('deleted_at')->count();
    $inProgressCount = \App\Models\Task::where('list_id', $listId)->where('status', 1)->whereNull('deleted_at')->count();
    $notStartedCount = \App\Models\Task::where('list_id', $listId)->where('status', 0)->whereNull('deleted_at')->count();

    $completedPct = $total > 0 ? round(($completedCount / $total) * 100) : 0;
    $inProgressPct = $total > 0 ? round(($inProgressCount / $total) * 100) : 0;
    $notStartedPct = $total > 0 ? round(($notStartedCount / $total) * 100) : 0;
@endphp

<!-- Dashboard Grid -->
<div class="grid gap-4" style="grid-template-columns: 1fr 380px;">

    <!-- LEFT: Active Tasks -->
    <div class="card p-4" style="min-height: 320px;">
        <div class="flex justify-between items-center mb-3">
            <h2 class="font-semibold text-white text-sm flex items-center gap-2">
                ⚡ Active Tasks
            </h2>
            <!-- Add Task Button triggers form below -->
            <button onclick="document.getElementById('add-task-form').classList.toggle('hidden')" class="btn-yellow">
                + Add Task
            </button>
        </div>

        <!-- Add Task Form (hidden by default) -->
        <div id="add-task-form" class="hidden mb-3 card p-3">
            <form method="POST" action="{{ route('tasks.store') }}" class="space-y-2">
                @csrf
                <input type="hidden" name="list_id" value="{{ $listId }}">
                <input type="text" name="task" placeholder="Task name..." required class="w-full text-sm" style="padding: 8px 12px;">
                <div class="flex gap-2">
                    <select name="priority" class="flex-1 text-sm">
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>
                    </select>
                    <input type="date" name="due_date" class="flex-1 text-sm">
                    <button type="submit" class="btn-yellow text-sm">Add</button>
                </div>
            </form>
        </div>

        <!-- Filters -->
        <form method="GET" class="flex gap-2 mb-3 flex-wrap">
            <input type="hidden" name="list_id" value="{{ $listId }}">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <select name="sort" class="text-xs">
                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Created</option>
                <option value="due_date" {{ request('sort') == 'due_date' ? 'selected' : '' }}>Due Date</option>
                <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>Priority</option>
            </select>
            <select name="order" class="text-xs">
                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
            </select>
            <button type="submit" class="text-xs text-slate-400 hover:text-white">Apply</button>
        </form>

        <!-- Active Task List -->
        @forelse($activeTasks as $task)
        <div class="task-card priority-{{ strtolower($task->priority) }}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="text-sm font-medium text-white mb-1">{{ $task->task }}</div>
                    <div class="flex gap-2 items-center flex-wrap">
                        <span class="status-badge status-{{ $task->status }}">
                            {{ ['Not Started', 'In Progress', 'Completed'][$task->status] }}
                        </span>
                        <span class="text-xs text-slate-500">{{ ucfirst(strtolower($task->priority)) }}</span>
                        @if($task->due_date)
                            <span class="text-xs text-slate-500">📅 {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1 ml-2">
                    <!-- Edit -->
                    <a href="{{ route('tasks.edit', $task) }}"
                       class="text-xs text-slate-400 hover:text-white px-2 py-1 rounded" style="background:#1e2d3d;">
                        ✏ Edit
                    </a>
                    <!-- Next Status -->
                    @if($task->status < 2)
                    <form method="POST" action="{{ route('tasks.status', $task->id) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ min($task->status + 1, 2) }}">
                        <button class="text-xs text-blue-400 hover:text-blue-300 px-2 py-1 rounded" style="background:#1e3a5f22;">
                            {{ $task->status == 0 ? '▶ Start' : '✓ Done' }}
                        </button>
                    </form>
                    @endif
                    <!-- Archive -->
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                        @csrf @method('DELETE')
                        <button class="delete-btn">Archive</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <span>🎉</span>
            <p class="text-sm">No active tasks!</p>
        </div>
        @endforelse
    </div>

    <!-- RIGHT COLUMN -->
    <div class="flex flex-col gap-4">

        <!-- Task Status Card -->
        <div class="card p-4">
            <h2 class="font-semibold text-white text-sm mb-4 flex items-center gap-2">
                ▪ Task Status
            </h2>
            <div class="flex justify-around">
                <div class="flex flex-col items-center gap-2">
                    <div class="circle-progress circle-completed">{{ $completedPct }}%</div>
                    <span class="text-xs text-slate-400">● Completed</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="circle-progress circle-inprogress">{{ $inProgressPct }}%</div>
                    <span class="text-xs text-slate-400">● In Progress</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="circle-progress circle-notstarted">{{ $notStartedPct }}%</div>
                    <span class="text-xs text-slate-400">● Not Started</span>
                </div>
            </div>
        </div>

        <!-- Completed Tasks Card -->
        <div class="card p-4 flex-1">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-semibold text-white text-sm flex items-center gap-2">
                    ▪ Completed Tasks
                </h2>
                <span class="badge-green">{{ $completedCount }}</span>
            </div>

            @forelse($completedTasks->take(4) as $task)
            <div class="task-card priority-{{ strtolower($task->priority) }} opacity-70">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-sm text-slate-400 line-through">{{ $task->task }}</div>
                        <span class="status-badge status-2 mt-1 inline-block">Completed</span>
                    </div>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                        @csrf @method('DELETE')
                        <button class="delete-btn">Archive</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding: 20px;">
                <span>📁</span>
                <p class="text-xs">No completed tasks yet.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>

<!-- Archived Tasks (Full Width) -->
<div class="card p-4 mt-4">
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-semibold text-white text-sm flex items-center gap-2">
            ♻ Archived Tasks
        </h2>
        <span class="badge-red">{{ $archivedTasks->count() }}</span>
    </div>

    @forelse($archivedTasks as $task)
    <div class="task-card opacity-60" style="border-left: 3px solid #f8717155;">
        <div class="flex justify-between items-center">
            <div>
                <div class="text-sm text-slate-400 line-through">{{ $task->task }}</div>
                <span class="text-xs text-slate-600">Archived {{ $task->deleted_at->diffForHumans() }}</span>
            </div>
            <div class="flex gap-1">
                <form method="POST" action="{{ route('tasks.restore', $task->id) }}">
                    @csrf @method('PATCH')
                    <button class="restore-btn">↩ Restore</button>
                </form>
                <form method="POST" action="{{ route('tasks.forceDelete', $task->id) }}" onsubmit="return confirm('Permanently delete this task?')">
                    @csrf @method('DELETE')
                    <button class="delete-btn">✕ Delete</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state" style="padding: 24px;">
        <span>✨</span>
        <p class="text-xs">No archived tasks.</p>
    </div>
    @endforelse
</div>

@endif
@endsection