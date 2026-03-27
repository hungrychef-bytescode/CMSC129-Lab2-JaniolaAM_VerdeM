@extends('layouts.app')

@section('content')

@if(!request('list_id'))
    <p class="text-gray-500">Select a list to manage tasks.</p>
@endif

@if(request('list_id'))

<!-- Filters -->
<div class="bg-white p-4 rounded shadow mb-4 flex flex-wrap gap-3 items-center">

    <form method="GET" class="flex gap-2 flex-wrap">
        <input type="hidden" name="list_id" value="{{ request('list_id') }}">

        <select name="status" class="border p-2 rounded">
            <option value="">All Status</option>
            <option value="0">Not Started</option>
            <option value="1">In Progress</option>
            <option value="2">Completed</option>
        </select>

        <select name="priority" class="border p-2 rounded">
            <option value="">All Priority</option>
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
        </select>

        <select name="sort" class="border p-2 rounded">
            <option value="">Sort</option>
            <option value="created_at">Created</option>
            <option value="due_date">Due Date</option>
            <option value="priority">Priority</option>
        </select>

        <button class="bg-blue-500 text-white px-4 rounded">Apply</button>
    </form>

    <!-- Toggle Archive -->
    <a href="{{ route('tasks.index', ['list_id'=>request('list_id'),'deleted'=>1]) }}"
       class="text-sm text-red-500 underline">
       View Archived
    </a>

</div>

<!-- Add Task -->
<div class="bg-white p-4 rounded shadow mb-4">
    <form method="POST" action="{{ route('tasks.store') }}" class="flex gap-2">
        @csrf
        <input type="hidden" name="list_id" value="{{ request('list_id') }}">

        <input name="task" placeholder="New task..."
               class="flex-1 border rounded p-2">

        <select name="priority" class="border p-2 rounded">
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
        </select>

        <input type="date" name="due_date" class="border p-2 rounded">

        <button class="bg-green-500 text-white px-4 rounded">
            Add
        </button>
    </form>
</div>

<!-- Tasks -->
<div class="space-y-3">
@forelse($tasks as $task)
    <div class="bg-white p-4 rounded shadow flex justify-between items-center">

        <div>
            <p class="font-semibold">{{ $task->task }}</p>

            <div class="text-sm text-gray-500">
                {{ $task->priority }} • 
                Status: {{ ['Not Started','In Progress','Completed'][$task->status] }}
            </div>
        </div>

        <div class="flex gap-2">

            @if(!$task->deleted_at)
                <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                    @csrf @method('DELETE')
                    <button class="text-red-500">Archive</button>
                </form>
            @else
                <form method="POST" action="{{ route('tasks.restore', $task->id) }}">
                    @csrf @method('PATCH')
                    <button class="text-green-500">Restore</button>
                </form>

                <form method="POST" action="{{ route('tasks.forceDelete', $task->id) }}">
                    @csrf @method('DELETE')
                    <button class="text-red-700">Delete</button>
                </form>
            @endif

        </div>

    </div>
@empty
    <p class="text-gray-400">No tasks found.</p>
@endforelse
</div>

<div class="mt-4">
    {{ $tasks->links() }}
</div>

@endif

@endsection