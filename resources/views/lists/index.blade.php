@extends('layouts.app')

@section('content')

<h2>Create List</h2>
<form method="POST" action="{{ route('lists.store') }}">
    @csrf
    <input type="text" name="name">
    <button>Create</button>
</form>

<hr>

@foreach($lists as $list)
    <div>
        {{ $list->name }}

        <form method="POST" action="{{ route('lists.destroy', $list) }}">
            @csrf
            @method('DELETE')
            <button>Delete</button>
        </form>
    </div>
@endforeach

@endsection