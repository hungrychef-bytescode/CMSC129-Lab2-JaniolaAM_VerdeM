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
        #chat-anchor {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 1000;
            width: 60px;
            height: 60px;
        }

        #chat-toggle {
            width: 60px; height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #eab308 0%, #f59e0b 100%);
            color: #000;
            border: 3px solid rgba(253,230,138,0.6);
            cursor: grab;
            font-size: 26px;
            box-shadow: 0 6px 28px rgba(234,179,8,0.5), 0 2px 10px rgba(0,0,0,0.5);
            display: flex; align-items: center; justify-content: center;
            transition: box-shadow 0.2s, transform 0.15s;
            user-select: none;
            position: relative;
            z-index: 2;
        }
        #chat-toggle:hover {
            box-shadow: 0 8px 36px rgba(234,179,8,0.7), 0 4px 12px rgba(0,0,0,0.4);
            transform: scale(1.07);
        }
        #chat-toggle:active { cursor: grabbing; transform: scale(0.97); }
        #chat-toggle::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 2px solid rgba(234,179,8,0.3);
            animation: pulseRing 2.5s ease-out infinite;
        }
        @keyframes pulseRing {
            0% { transform: scale(1); opacity: 0.7; }
            70% { transform: scale(1.35); opacity: 0; }
            100% { transform: scale(1.35); opacity: 0; }
        }

        #chat-window {
            position: absolute;
            bottom: calc(100% + 14px);
            right: 0;
            width: 360px;
            height: 500px;
            background: #141f2e;
            border: 1px solid #253447;
            border-radius: 18px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 16px 50px rgba(0,0,0,0.7), 0 4px 16px rgba(0,0,0,0.3);
            overflow: hidden;
            transform-origin: bottom right;
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), opacity 0.2s ease;
            z-index: 1;
            resize: both;
            min-width: 280px;
            min-height: 360px;
        }
        #chat-window.hidden {
            transform: scale(0.85) translateY(10px);
            opacity: 0;
            pointer-events: none;
        }
        #chat-window.visible {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        #chat-header {
            background: linear-gradient(135deg, #1a2d40 0%, #1a3530 100%);
            padding: 13px 16px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #253447;
            cursor: move;
            flex-shrink: 0;
            user-select: none;
        }
        #chat-header-left { display: flex; align-items: center; gap: 9px; }
        .chat-status-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #4ade80;
            box-shadow: 0 0 8px #4ade80;
            animation: blink 2s ease-in-out infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; } 50% { opacity: 0.4; }
        }
        #chat-title { font-size: 13px; font-weight: 600; color: #e2e8f0; letter-spacing: 0.2px; }
        #chat-subtitle { font-size: 10px; color: #64748b; margin-top: 1px; }
        #chat-header-actions { display: flex; gap: 4px; }
        .hbtn {
            background: none; border: none; cursor: pointer;
            color: #64748b; font-size: 13px;
            width: 26px; height: 26px; border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s;
        }
        .hbtn:hover { background: #253447; color: #e2e8f0; }

        #chat-messages {
            flex: 1; overflow-y: auto;
            padding: 14px 12px;
            display: flex; flex-direction: column; gap: 10px;
            scroll-behavior: smooth;
        }
        #chat-messages::-webkit-scrollbar { width: 3px; }
        #chat-messages::-webkit-scrollbar-thumb { background: #253447; border-radius: 2px; }

        .chat-msg {
            max-width: 82%;
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 13px;
            line-height: 1.55;
            word-break: break-word;
            animation: msgIn 0.2s ease;
        }
        @keyframes msgIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .chat-msg.user {
            align-self: flex-end;
            background: linear-gradient(135deg, #eab308, #d97706);
            color: #000;
            border-bottom-right-radius: 4px;
            font-weight: 500;
        }
        .chat-msg.ai {
            align-self: flex-start;
            background: #1e2d3d;
            color: #e2e8f0;
            border: 1px solid #253447;
            border-bottom-left-radius: 4px;
        }
        .typing-dots span {
            display: inline-block;
            width: 5px; height: 5px;
            border-radius: 50%;
            background: #64748b;
            margin: 0 2px;
            animation: dot 1.2s ease-in-out infinite;
        }
        .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes dot {
            0%, 80%, 100% { transform: scale(0.7); opacity: 0.4; }
            40% { transform: scale(1.1); opacity: 1; }
        }

        #chat-suggestions {
            display: flex; flex-wrap: wrap; gap: 6px;
            padding: 0 12px 8px;
            flex-shrink: 0;
        }
        .chip {
            background: #1e2d3d;
            border: 1px solid #253447;
            color: #94a3b8;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.15s;
            font-family: inherit;
        }
        .chip:hover { background: #253447; color: #e2e8f0; border-color: #eab308; }

        #chat-input-area {
            padding: 10px 12px 12px;
            border-top: 1px solid #1e2d3d;
            display: flex; gap: 8px; flex-shrink: 0;
            background: #141f2e;
        }
        #chat-input {
            flex: 1;
            background: #1e2d3d;
            border: 1px solid #253447;
            color: #e2e8f0;
            border-radius: 10px;
            padding: 9px 13px;
            font-size: 13px;
            outline: none;
            transition: border-color 0.15s;
            font-family: inherit;
            resize: none;
            max-height: 80px;
        }
        #chat-input:focus { border-color: #eab308; }
        #chat-send {
            background: linear-gradient(135deg, #eab308, #ca8a04);
            color: #000;
            border: none;
            border-radius: 10px;
            padding: 9px 15px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.1s;
            align-self: flex-end;
            font-family: inherit;
        }
        #chat-send:hover { opacity: 0.88; }
        #chat-send:active { transform: scale(0.95); }
        #chat-send:disabled { opacity: 0.35; cursor: not-allowed; }

        #unread-badge {
            position: absolute;
            top: -2px; right: -2px;
            width: 18px; height: 18px;
            background: #ef4444;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
            display: none;
            align-items: center; justify-content: center;
            border: 2px solid #0f1923;
            z-index: 3;
        }
        #unread-badge.show { display: flex; }
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
<div id="chat-anchor">
    <div id="unread-badge">1</div>
    <button id="chat-toggle" title="Open AI Assistant">🤖</button>
    <div id="chat-window" class="hidden">
        <div id="chat-header">
            <div id="chat-header-left">
                <div class="chat-status-dot"></div>
                <div>
                    <div id="chat-title">heyToday! Assistant</div>
                    <div id="chat-subtitle">Always here to help</div>
                </div>
            </div>
            <div id="chat-header-actions">
                <button class="hbtn" id="chat-expand" title="Expand">⛶</button>
                <button class="hbtn" id="chat-close" title="Close">✕</button>
            </div>
        </div>
        <div id="chat-messages">
            <div class="chat-msg ai">Hi! I'm your task assistant. Ask me anything about your tasks! 👋</div>
        </div>
        <div id="chat-suggestions">
            <button class="chip" onclick="fillInput('What tasks are due today?')">📅 Due today</button>
            <button class="chip" onclick="fillInput('Show high priority tasks')">🔴 High priority</button>
            <button class="chip" onclick="fillInput('What did I complete?')">✅ Completed</button>
        </div>
        <div id="chat-input-area">
            <textarea id="chat-input" rows="1" placeholder="Ask about your tasks..."></textarea>
            <button id="chat-send">Send</button>
        </div>
    </div>
</div>

<script>
    const anchor   = document.getElementById('chat-anchor');
    const toggle   = document.getElementById('chat-toggle');
    const chatWin  = document.getElementById('chat-window');
    const closeBtn = document.getElementById('chat-close');
    const expandBtn = document.getElementById('chat-expand');
    const input    = document.getElementById('chat-input');
    const sendBtn  = document.getElementById('chat-send');
    const messages = document.getElementById('chat-messages');
    const badge    = document.getElementById('unread-badge');

    let isOpen = false;
    let isExpanded = false;
    let conversationHistory = [];

    // --- open / close ---
    function openChat() {
        isOpen = true;
        chatWin.classList.remove('hidden');
        setTimeout(() => chatWin.classList.add('visible'), 10);
        badge.classList.remove('show');
        input.focus();
    }
    function closeChat() {
        isOpen = false;
        chatWin.classList.remove('visible');
        setTimeout(() => chatWin.classList.add('hidden'), 250);
    }

    closeBtn.addEventListener('click', closeChat);

    expandBtn.addEventListener('click', () => {
        isExpanded = !isExpanded;
        if (isExpanded) {
            chatWin.style.width = '480px';
            chatWin.style.height = '640px';
            expandBtn.textContent = '⊠';
        } else {
            chatWin.style.width = '360px';
            chatWin.style.height = '500px';
            expandBtn.textContent = '⛶';
        }
    });

    // --- drag logic (ONE mousemove, ONE mouseup, NO click listener) ---
    let dragging  = false;
    let wasDragged = false;
    let startX, startY, origRight, origBottom;

    toggle.addEventListener('mousedown', (e) => {
        dragging   = true;
        wasDragged = false;
        startX     = e.clientX;
        startY     = e.clientY;
        const rect = anchor.getBoundingClientRect();
        origRight  = window.innerWidth  - rect.right;
        origBottom = window.innerHeight - rect.bottom;
        e.preventDefault(); // prevents the browser firing a 'click' event after mouseup
    });

    document.addEventListener('mousemove', (e) => {
        if (!dragging) return;
        const dx = e.clientX - startX;
        const dy = e.clientY - startY;
        if (Math.abs(dx) > 8 || Math.abs(dy) > 8) wasDragged = true;
        anchor.style.right  = Math.max(8, origRight  - dx) + 'px';
        anchor.style.bottom = Math.max(8, origBottom - dy) + 'px';
        anchor.style.left   = 'auto';
        anchor.style.top    = 'auto';
    });

    document.addEventListener('mouseup', () => {
        if (dragging && !wasDragged) {
            isOpen ? closeChat() : openChat();
        }
        dragging   = false;
        wasDragged = false;
    });

    // --- textarea auto-resize ---
    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 80) + 'px';
    });

    function fillInput(text) {
        input.value = text;
        input.focus();
    }

    function addMessage(text, role) {
        const div = document.createElement('div');
        div.className = `chat-msg ${role}`;
        if (role === 'ai loading') {
            div.innerHTML = '<div class="typing-dots"><span></span><span></span><span></span></div>';
        } else {
            div.textContent = text;
        }
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
        return div;
    }

    async function sendMessage() {
        const text = input.value.trim();
        if (!text) return;
        input.value = '';
        input.style.height = 'auto';
        sendBtn.disabled = true;
        addMessage(text, 'user');
        conversationHistory.push({ role: 'user', content: text });
        const loading = addMessage('', 'ai loading');

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
            if (!isOpen) { badge.classList.add('show'); }
        } catch (err) {
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