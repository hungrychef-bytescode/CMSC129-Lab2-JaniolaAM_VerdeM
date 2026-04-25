<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskList;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $listId  = $request->input('list_id');
        $history = $request->input('history', []);

        // --- Gather context from DB ---
        $context = '';
        if ($listId) {
            $list  = TaskList::find($listId);
            $tasks = Task::where('list_id', $listId)->whereNull('deleted_at')->get();

            $taskLines = $tasks->map(function ($t) {
                $status = ['Not Started', 'In Progress', 'Completed'][$t->status] ?? 'Unknown';
                $due    = $t->due_date ? \Carbon\Carbon::parse($t->due_date)->format('M d, Y') : 'No due date';
                return "- [{$status}] {$t->task} | Priority: {$t->priority} | Due: {$due}";
            })->join("\n");

            $total     = $tasks->count();
            $completed = $tasks->where('status', 2)->count();
            $inProg    = $tasks->where('status', 1)->count();
            $notStart  = $tasks->where('status', 0)->count();

            $context = "List: \"{$list->name}\"\nTotal tasks: {$total} (Completed: {$completed}, In Progress: {$inProg}, Not Started: {$notStart})\n\nTasks:\n{$taskLines}";
        } else {
            $lists = TaskList::withCount('tasks')->get();
            $listLines = $lists->map(fn($l) => "- {$l->name} ({$l->tasks_count} tasks)")->join("\n");
            $context = "Available task lists:\n{$listLines}";
        }

        // --- Build messages for AI ---
        $systemPrompt = "You are a helpful task management assistant for the heyToday! app. Answer questions about the user's tasks based on the data provided. Be concise and friendly.\n\nCurrent data:\n{$context}";

        $apiKey = env('GEMINI_API_KEY');

        // Build Gemini contents array
        $contents = [];

        // Add history
        foreach ($history as $msg) {
            if (in_array($msg['role'], ['user', 'assistant'])) {
                $contents[] = [
                    'role'  => $msg['role'] === 'assistant' ? 'model' : 'user',
                    'parts' => [['text' => $msg['content']]]
                ];
            }
        }

        // Add current message
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $message]]
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                'contents'           => $contents,
                'generationConfig'   => ['maxOutputTokens' => 500, 'temperature' => 0.7],
            ]);

            $data  = $response->json();
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not generate a response.';
        } catch (\Exception $e) {
            $reply = 'Sorry, I encountered an error. Please try again.';
        }

        return response()->json(['reply' => $reply]);
    }
}
