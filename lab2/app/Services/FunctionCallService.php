<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskList;

class FunctionCallService
{
    public function handle($json)
    {
        $action = $json['action'] ?? 'unknown';
        $data = $json['data'] ?? [];

        switch ($action) {

            case 'create_task':
                return $this->createTask($data);

            case 'update_task':
                return $this->updateTask($data);

            case 'delete_task':
                return $this->prepareDelete($data);

            case 'confirm_delete':
                return $this->confirmDelete();

            case 'query_tasks':
                return $this->queryTasks($data);

            case 'list_tasks':
                return $this->listTasks();

            default:
                return "I did not understand the request.";
        }
    }

    //create task
    private function createTask($data)
    {
        $list = TaskList::first();

        if (!$list) {
            $list = TaskList::create([
                'name' => 'Default'
            ]);
        }

        $task = Task::create([
            'task' => $data['task'] ?? 'Untitled',
            'description' => $data['description'] ?? null,
            'priority' => $data['priority'] ?? 'Medium',
            'due_date' => $data['due_date'] ?? null,
            'status' => $data['status'] ?? 0,
            'list_id' => $data['list_id'] ?? $list->id
        ]);

        return "Created task: {$task->task} (List: {$list->name})";
    }

    //update task
    private function updateTask($data)
    {
        $task = Task::find($data['id'] ?? null);

        if (!$task) {
            return "Task not found.";
        }

        $task->update($data);

        return "Updated task: {$task->task}";
    }

    // Prepare delete (soft delete)
    private function prepareDelete($data)
    {
        $task = Task::find($data['id'] ?? null);

        if (!$task) {
            return "Task not found.";
        }

        session(['pending_delete' => $task->id]);

        return "Confirm delete '{$task->task}' by typing: confirm delete";
    }

    //soft delete
    private function confirmDelete()
    {
        $id = session('pending_delete');

        if (!$id) {
            return "No pending delete.";
        }

        $task = Task::find($id);

        if ($task) {
            $task->delete();
        }

        session()->forget('pending_delete');

        return "Task archived.";
    }

    /**
     * Query tasks with filters
     */
    private function queryTasks($data)
    {
        $query = Task::query();

        // Include archived tasks if requested
        if (!empty($data['archived'])) {
            $query->onlyTrashed();
        }

        // Filter by list
        if (isset($data['list_id'])) {
            $query->where('list_id', $data['list_id']);
        }

        // Filter by priority
        if (isset($data['priority'])) {
            $query->where('priority', $data['priority']);
        }

        // Filter by status
        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        // Filter due today
        if (!empty($data['due_today'])) {
            $query->whereDate('due_date', now());
        }

        $tasks = $query->get();

        if ($tasks->isEmpty()) {
            return "No tasks found.";
        }

        $output = "Tasks:\n";

        foreach ($tasks as $task) {
            $state = $task->deleted_at ? "Archived" : "Active";
            $status = $task->status ? "Done" : "Pending";

            $output .= "#{$task->id} {$task->task} ({$task->priority}) - {$status} - {$state}\n";
        }

        return $output;
    }

    private function listTasks()
    {
        return $this->queryTasks([]);
    }
}