<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\TaskAPIController;

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
                return $this->prepareArchive($data);

            case 'confirm_archive':
                return $this->confirmArchive();

            case 'restore_task':
                return $this->restoreTask($data);

            case 'force_delete_task':
                return $this->prepareForceDelete($data);

            case 'confirm_force_delete':
                return $this->confirmForceDelete();

            case 'query_tasks':
                return $this->queryTasks($data);

            case 'list_tasks':
                return $this->listTasks();

            default:
                return "I did not understand the request.";
        }
    }

    private function createTask($data)
    {
        $controller = new TaskAPIController();
        $request = new Request($data);

        $controller->store($request);

        return "Task created.";
    }

    private function updateTask($data)
    {
        if (!isset($data['id'])) return "Task ID required.";

        $controller = new TaskAPIController();
        $request = new Request($data);

        $controller->update($request, $data['id']);

        return "Task updated.";
    }

    private function prepareArchive($data)
    {
        session(['pending_archive' => $data['id'] ?? null]);
        return "Confirm archive by typing: confirm archive";
    }

    private function confirmArchive()
    {
        $id = session('pending_archive');

        if (!$id) return "No pending archive.";

        $controller = new TaskAPIController();
        $controller->archive($id);

        session()->forget('pending_archive');

        return "Task archived.";
    }

    private function restoreTask($data)
    {
        if (!isset($data['id'])) return "Task ID required.";

        $controller = new TaskAPIController();
        $controller->restore($data['id']);

        return "Task restored.";
    }

    private function prepareForceDelete($data)
    {
        session(['pending_force_delete' => $data['id'] ?? null]);
        return "Confirm permanent delete by typing: confirm delete forever";
    }

    private function confirmForceDelete()
    {
        $id = session('pending_force_delete');

        if (!$id) return "No pending delete.";

        $controller = new TaskAPIController();
        $controller->forceDelete($id);

        session()->forget('pending_force_delete');

        return "Task permanently deleted.";
    }

    private function queryTasks($data)
    {
        $controller = new TaskAPIController();
        $request = new Request($data);

        $response = $controller->index($request);
        $tasks = $response->getData(true);

        if (empty($tasks)) return "No tasks found.";

        $output = "Tasks:\n";

        foreach ($tasks as $task) {
            $state = isset($task['deleted_at']) && $task['deleted_at']
                ? "Archived"
                : "Active";

            $status = $task['status'] ? "Done" : "Pending";

            $output .= "#{$task['id']} {$task['task']} ({$task['priority']}) - {$status} - {$state}\n";
        }

        return $output;
    }

    private function listTasks()
    {
        return $this->queryTasks([]);
    }
}