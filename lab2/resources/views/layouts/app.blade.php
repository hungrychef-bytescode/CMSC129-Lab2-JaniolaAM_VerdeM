<!DOCTYPE html>
<html>
<head>
    <title>Todo App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-4">
        <h2 class="text-xl font-bold mb-4">My Lists</h2>

        <form method="POST" action="{{ route('lists.store') }}" class="mb-4">
            @csrf
            <input name="name" placeholder="New list..."
                   class="w-full border rounded p-2 mb-2">
            <button class="w-full bg-blue-500 text-white p-2 rounded">
                + Add List
            </button>
        </form>

        <div class="space-y-2">
            @foreach($lists as $list)
                <a href="{{ route('tasks.index', ['list_id' => $list->id]) }}"
                   class="block p-2 rounded hover:bg-blue-100
                   {{ request('list_id') == $list->id ? 'bg-blue-200 font-semibold' : '' }}">
                    {{ $list->name }}
                </a>
            @endforeach
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-6 overflow-y-auto">
        
        <!-- Topbar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Tasks</h1>

            <!-- Global Search -->
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Search..."
                       class="border rounded p-2"
                       value="{{ request('search') }}">
                <button class="bg-gray-800 text-white px-4 rounded">
                    Search
                </button>
            </form>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')

    </main>
</div>

</body>
</html>