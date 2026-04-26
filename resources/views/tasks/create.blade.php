@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('tasks.store') }}">
    @csrf

    <input type="text" name="task" placeholder="Task">

    <select name="priority">
        <option>Low</option>
        <option>Medium</option>
        <option>High</option>
    </select>

    <input type="date" name="due_date">

    <select name="list_id">
        @foreach($lists as $list)
            <option value="{{ $list->id }}">{{ $list->name }}</option>
        @endforeach
    </select>

    <button>Create</button>
</form>

@endsection