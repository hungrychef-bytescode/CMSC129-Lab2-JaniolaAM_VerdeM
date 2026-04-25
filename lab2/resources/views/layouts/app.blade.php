<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>heyToday!</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        .logo-font { font-family: 'Space Mono', monospace; }
        body { background-color: #0f1923; color: #e2e8f0; }

        .sidebar { background-color: #141f2e; border-right: 1px solid #1e2d3d; }
        .card { background-color: #141f2e; border: 1px solid #1e2d3d; border-radius: 12px; }
        .list-item { border-radius: 8px; transition: all 0.2s; }
        .list-item:hover { background-color: #1e2d3d; }
        .list-item.active { background-color: #1e3a2f; border-left: 3px solid #4ade80; }

        .circle-progress {
            width: 70px; height: 70px;
            border-radius: 50%;
            border: 3px solid #1e2d3d;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700;
            position: relative;
        }
        .circle-completed { border-color: #4ade80; color: #4ade80; }
        .circle-inprogress { border-color: #60a5fa; color: #60a5fa; }
        .circle-notstarted { border-color: #94a3b8; color: #94a3b8; }

        .badge-green { background-color: #166534; color: #4ade80; border-radius: 20px; padding: 2px 10px; font-size: 12px; }
        .badge-red { background-color: #7f1d1d; color: #f87171; border-radius: 20px; padding: 2px 10px; font-size: 12px; }
        .badge-yellow { background-color: #eab308; color: #000; border-radius: 20px; padding: 2px 10px; font-size: 12px; font-weight: 700; }

        .btn-yellow { background-color: #eab308; color: #000; border-radius: 8px; padding: 6px 16px; font-weight: 600; font-size: 13px; transition: all 0.2s; border: none; cursor: pointer; }
        .btn-yellow:hover { background-color: #ca8a04; }

        select, input[type="text"], input[type="date"] {
            background-color: #1e2d3d;
            border: 1px solid #2d3d50;
            color: #e2e8f0;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 13px;
        }
        select:focus, input:focus { outline: none; border-color: #4ade80; }

        .task-card { background-color: #1a2637; border: 1px solid #1e2d3d; border-radius: 8px; padding: 12px 16px; margin-bottom: 8px; transition: all 0.2s; }
        .task-card:hover { border-color: #2d3d50; transform: translateY(-1px); }

        .priority-high { border-left: 3px solid #f87171; }
        .priority-medium { border-left: 3px solid #eab308; }
        .priority-low { border-left: 3px solid #4ade80; }

        .status-badge { font-size: 11px; padding: 2px 8px; border-radius: 20px; font-weight: 500; }
        .status-0 { background-color: #1e2d3d; color: #94a3b8; }
        .status-1 { background-color: #1e3a5f; color: #60a5fa; }
        .status-2 { background-color: #1e3a2f; color: #4ade80; }

        .delete-btn { color: #f87171; background: none; border: none; cursor: pointer; font-size: 13px; padding: 3px 8px; border-radius: 4px; transition: all 0.2s; }
        .delete-btn:hover { background-color: #7f1d1d33; }
        .restore-btn { color: #4ade80; background: none; border: none; cursor: pointer; font-size: 13px; padding: 3px 8px; border-radius: 4px; transition: all 0.2s; }
        .restore-btn:hover { background-color: #166534; }

        .new-list-input { background-color: #1e2d3d; border: 1px solid #2d3d50; color: #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 13px; width: 100%; }
        .new-list-input:focus { outline: none; border-color: #4ade80; }
        .add-list-btn { background-color: #eab308; color: #000; border: none; border-radius: 8px; padding: 8px 12px; font-weight: 700; cursor: pointer; font-size: 16px; }
        .add-list-btn:hover { background-color: #ca8a04; }

        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; color: #475569; }
        .empty-state span { font-size: 36px; margin-bottom: 8px; }

        .summary-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }

        .active-tasks-indicator { background-color: #166534; color: #4ade80; border-radius: 20px; padding: 4px 14px; font-size: 12px; font-weight: 600; }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #0f1923; }
        ::-webkit-scrollbar-thumb { background: #1e2d3d; border-radius: 2px; }

        .alert-success { background-color: #1e3a2f; border: 1px solid #166534; color: #4ade80; border-radius: 8px; padding: 10px 16px; margin-bottom: 16px; font-size: 14px; }

        /* ===== CHAT WIDGET STYLES ===== */
        #chat-toggle {
            position: fixed;
            bottom: 28px; right: 28px;
            z-index: 1000;
            width: 52px; height: 52px;
            border-radius: 50%;
            background-color: #eab308;
            color: #000;
            border: none;
            cursor: pointer;
            font-size: 22px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        #chat-toggle:hover { background-color: #ca8a04; transform: scale(1.08); }
        #chat-window {
            position: fixed;
            bottom: 90px; right: 28px;
            z-index: 999;
            width: 340px;
            max-height: 480px;
            background-color: #141f2e;
            border: 1px solid #1e2d3d;
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            overflow: hidden;
        }
        #chat-window.hidden { display: none; }
        #chat-header {
            background-color: #1e2d3d;
            padding: 12px 16px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #2d3d50;
        }
        #chat-header span { font-size: 13px; font-weight: 600; color: #e2e8f0; }
        #chat-close { background: none; border: none; color: #94a3b8; cursor: pointer; font-size: 16px; }
        #chat-close:hover { color: #e2e8f0; }
        #chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .chat-msg {
            max-width: 85%;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 13px;
            line-height: 1.5;
            word-break: break-word;
        }
        .chat-msg.user {
            align-self: flex-end;
            background-color: #eab308;
            color: #000;
            border-bottom-right-radius: 2px;
        }
        .chat-msg.ai {
            align-self: flex-start;
            background-color: #1e2d3d;
            color: #e2e8f0;
            border-bottom-left-radius: 2px;
        }
        .chat-msg.loading { color: #94a3b8; font-style: italic; }
        #chat-input-area {
            padding: 10px 12px;
            border-top: 1px solid #1e2d3d;
            display: flex;
            gap: 8px;
        }
        #chat-input {
            flex: 1;
            background-color: #1e2d3d;
            border: 1px solid #2d3d50;
            color: #e2e8f0;
            border-radius: 8px;
            padding: 7px 10px;
            font-size: 13px;
            outline: none;
        }
        #chat-input:focus { border-color: #eab308; }
        #chat-send {
            background-color: #eab308;
            color: #000;
            border: none;
            border-radius: 8px;
            padding: 7px 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }
        #chat-send:hover { background-color: #ca8a04; }
        #chat-send:disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body class="h-screen flex overflow-hidden">

<!-- Sidebar -->
<aside class="sidebar w-56 flex flex-col h-screen overflow-y-auto flex-shrink-0 p-4">

    <!-- Logo -->
    <div class="mb-6">
        <div class="logo-font text-yellow-400 font-bold text-lg flex items-center gap-1">
            ✦ heyToday!
        </div>
        <div class="text-xs text-slate-500 mt-0.5">Stay focused, get it done.</div>
    </div>

    <!-- My Lists -->
    <div class="mb-4">
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">My Lists</div>
        <div class="space-y-1">
            @foreach($lists as $list)
                <div class="list-item flex items-center justify-between px-2 py-2 {{ request('list_id') == $list->id ? 'active' : '' }}">
                    <a href="{{ route('tasks.index', ['list_id' => $list->id]) }}"
                       class="flex items-center gap-2 flex-1 text-sm {{ request('list_id') == $list->id ? 'text-green-400 font-semibold' : 'text-slate-300' }}">
                        <span class="text-xs">▪</span>
                        {{ $list->name }}
                    </a>
                    <form method="POST" action="{{ route('lists.destroy', $list) }}">
                        @csrf @method('DELETE')
                        <button class="text-slate-600 hover:text-red-400 text-xs ml-1" title="Delete list">✕</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Add New List -->
    <div class="mt-auto">
        <form method="POST" action="{{ route('lists.store') }}" class="flex gap-2 mb-4">
            @csrf
            <input name="name" placeholder="New list..." class="new-list-input flex-1">
            <button type="submit" class="add-list-btn">+</button>
        </form>

        <!-- Summary -->
        @if(request('list_id'))
        @php
            $listId = request('list_id');
            $totalTasks = \App\Models\Task::where('list_id', $listId)->count();
            $doneTasks = \App\Models\Task::where('list_id', $listId)->where('status', 2)->count();
            $activeTasks = \App\Models\Task::where('list_id', $listId)->where('status', '!=', 2)->count();
        @endphp
        <div>
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Summary</div>
            <div class="space-y-1.5 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Total</span>
                    <span class="summary-dot bg-slate-400"></span>
                    <span class="text-slate-300 font-semibold">{{ $totalTasks }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Done</span>
                    <span class="summary-dot bg-green-400"></span>
                    <span class="text-green-400 font-semibold">{{ $doneTasks }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Active</span>
                    <span class="summary-dot bg-yellow-400"></span>
                    <span class="text-yellow-400 font-semibold">{{ $activeTasks }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>

</aside>

<!-- Main Content -->
<main class="flex-1 overflow-y-auto p-6">

    <!-- Top Bar -->
    @if(request('list_id'))
    @php $currentList = \App\Models\TaskList::find(request('list_id')); @endphp
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="text-xs text-slate-500 mb-0.5">{{ now()->format('l, d F Y') }}</div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span class="text-slate-500">▪</span>
                {{ $currentList->name ?? 'Tasks' }}
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex gap-2">
                <input type="hidden" name="list_id" value="{{ request('list_id') }}">
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                @if(request('order')) <input type="hidden" name="order" value="{{ request('order') }}"> @endif
                <input type="text" name="search" placeholder="Search tasks..." value="{{ request('search') }}" class="text-sm" style="width:200px">
                <button type="submit" class="btn-yellow text-xs px-3">🔍</button>
            </form>
            @php $activeCount = \App\Models\Task::where('list_id', request('list_id'))->where('status','!=',2)->whereNull('deleted_at')->count(); @endphp
            <span class="active-tasks-indicator">● {{ $activeCount }} active tasks</span>
        </div>
    </div>
    @else
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-white">Select a list to get started</h1>
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" placeholder="Search tasks..." value="{{ request('search') }}" class="text-sm" style="width:200px">
        </form>
    </div>
    @endif

    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    @yield('content')

</main>

<!-- ===== CHAT WIDGET ===== -->
<button id="chat-toggle" title="Open AI Assistant">🤖</button>

<div id="chat-window" class="hidden">
    <div id="chat-header">
        <span>🤖 heyToday! Assistant</span>
        <button id="chat-close">✕</button>
    </div>
    <div id="chat-messages">
        <div class="chat-msg ai">Hi! I'm your task assistant. Ask me anything about your tasks! 👋</div>
    </div>
    <div id="chat-input-area">
        <input id="chat-input" type="text" placeholder="Ask about your tasks..." />
        <button id="chat-send">Send</button>
    </div>
</div>

<script>
    const toggle   = document.getElementById('chat-toggle');
    const chatWin  = document.getElementById('chat-window');
    const closeBtn = document.getElementById('chat-close');
    const input    = document.getElementById('chat-input');
    const sendBtn  = document.getElementById('chat-send');
    const messages = document.getElementById('chat-messages');

    let conversationHistory = [];

    toggle.addEventListener('click', () => chatWin.classList.toggle('hidden'));
    closeBtn.addEventListener('click', () => chatWin.classList.add('hidden'));

    function addMessage(text, role) {
        const div = document.createElement('div');
        div.className = `chat-msg ${role}`;
        div.textContent = text;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
        return div;
    }

    async function sendMessage() {
        const text = input.value.trim();
        if (!text) return;

        input.value = '';
        sendBtn.disabled = true;
        addMessage(text, 'user');
        conversationHistory.push({ role: 'user', content: text });

        const loading = addMessage('Thinking...', 'ai loading');

        try {
            const listId = new URLSearchParams(window.location.search).get('list_id');
            const res = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message: text,
                    list_id: listId,
                    history: conversationHistory.slice(-10)
                })
            });
            const data = await res.json();
            loading.remove();
            const reply = data.reply || 'Sorry, I could not process that.';
            addMessage(reply, 'ai');
            conversationHistory.push({ role: 'assistant', content: reply });
        } catch (e) {
            loading.remove();
            addMessage('Error: Could not connect to assistant.', 'ai');
        }

        sendBtn.disabled = false;
        input.focus();
    }

    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
    });
</script>

</body>
</html>