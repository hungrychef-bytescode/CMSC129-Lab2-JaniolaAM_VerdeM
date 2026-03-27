<div class="bg-gray-800 p-4 rounded mb-2">

    <div class="{{ $task->status == 2 ? 'line-through' : '' }}">
        {{ $task->task }}
    </div>

    <div class="text-sm text-gray-400">
        {{ $task->priority }} |
        {{ $task->list->name ?? 'No List' }}
    </div>

    <div class="flex gap-2 mt-2">

        {{-- STATUS --}}
        <form method="POST" action="{{ route('tasks.status', $task->id) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ min($task->status+1,2) }}">
            <button class="bg-blue-600 px-2 py-1 text-xs">
                Next
            </button>
        </form>

        {{-- DELETE --}}
        <form method="POST" action="{{ route('tasks.destroy', $task->id) }}">
            @csrf
            @method('DELETE')
            <button class="bg-red-600 px-2 py-1 text-xs">
                Delete
            </button>
        </form>

    </div>

</div>