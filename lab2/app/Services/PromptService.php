<?php

namespace App\Services;

use App\Models\TaskList;

class PromptService
{
    public function buildPrompt($message, $history = [])
    {
        $lists = TaskList::getAllWithAllTasks()->map(function ($list) {
            return [
                'id' => $list->id,
                'name' => $list->name,
                'tasks' => $list->allTasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'task' => $task->task,
                        'priority' => $task->priority,
                        'status' => $task->status,
                        'archived' => $task->deleted_at ? true : false
                    ];
                })
            ];
        });

        return "
You are an AI Task Assistant.

Return ONLY JSON.

ACTIONS:
- create_task
- update_task
- delete_task
- confirm_delete
- query_tasks
- list_tasks

RULES:
- ALWAYS JSON
- archived = true means task is deleted

EXAMPLES:

User: show archived tasks
{
  \"action\": \"query_tasks\",
  \"data\": {
    \"archived\": true
  }
}

CURRENT DATA:
" . json_encode($lists) . "

USER:
\"$message\"
";
    }
}