@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="card p-6">
        <h2 class="font-semibold text-white text-base mb-4 flex items-center gap-2">
            ✏️ Edit Task
        </h2>

        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs text-slate-400 mb-1">Task Name</label>
                <input type="text" name="task" value="{{ old('task', $task->task) }}" required
                       class="w-full text-sm" style="padding: 8px 12px;">
                @error('task') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <div class="flex-1">
                    <label class="block text-xs text-slate-400 mb-1">Priority</label>
                    <select name="priority" class="w-full text-sm">
                        @foreach(['Low','Medium','High'] as $p)
                            <option value="{{ $p }}" {{ old('priority', $task->priority) == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-xs text-slate-400 mb-1">Status</label>
                    <select name="status" class="w-full text-sm">
                        <option value="0" {{ old('status', $task->status) == 0 ? 'selected' : '' }}>Not Started</option>
                        <option value="1" {{ old('status', $task->status) == 1 ? 'selected' : '' }}>In Progress</option>
                        <option value="2" {{ old('status', $task->status) == 2 ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs text-slate-400 mb-1">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date', $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '') }}"
                       class="w-full text-sm" style="padding: 8px 12px;">
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="btn-yellow">Save Changes</button>
                <a href="{{ route('tasks.index', ['list_id' => $task->list_id]) }}"
                   class="text-sm text-slate-400 hover:text-white px-4 py-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
