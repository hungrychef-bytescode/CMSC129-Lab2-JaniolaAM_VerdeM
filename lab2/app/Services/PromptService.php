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

        $historyText = json_encode($history);

        return "
You are an AI Task Assistant.

You MUST return ONLY valid JSON.
Do NOT include explanations.
Do NOT wrap in markdown.
Do NOT use ```json.

ACTIONS:
- create_task
- update_task
- delete_task
- confirm_delete
- query_tasks
- list_tasks
- unknown

RULES:
- ALWAYS return valid JSON
- If unsure, return:
  {
    \"action\": \"unknown\",
    \"data\": {}
  }

- archived = true means task is deleted
- status: 0 = not started, 1 = in progress, 2 = completed

LIST FILTERING RULE:
- If user mentions a list name (e.g., Work, School), find matching list_id
- Include it in:
  \"list_id\": <id>

FILTER COMBINATION RULE:
- You may combine filters:
  list_id, priority, status, archived, due_today

EXAMPLES:

User: show tasks in Work
{
  \"action\": \"query_tasks\",
  \"data\": {
    \"list_id\": 1
  }
}

User: show archived tasks
{
  \"action\": \"query_tasks\",
  \"data\": {
    \"archived\": true
  }
}

User: show high priority tasks in School
{
  \"action\": \"query_tasks\",
  \"data\": {
    \"list_id\": 2,
    \"priority\": \"High\"
  }
}

User: delete task 3
{
  \"action\": \"delete_task\",
  \"data\": {
    \"id\": 3
  }
}

User: confirm delete
{
  \"action\": \"confirm_delete\",
  \"data\": {}
}

CONTEXT (previous messages):
$historyText

CURRENT DATA:
" . json_encode($lists) . "

USER:
\"$message\"
";
    }
}