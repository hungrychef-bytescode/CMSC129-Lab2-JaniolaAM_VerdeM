<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\TaskList;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $lists = TaskList::all();

        $tasks = [
            ['task' => 'Finish Laravel project', 'priority' => 'High'],
            ['task' => 'Submit lab report', 'priority' => 'High'],
            ['task' => 'Buy groceries', 'priority' => 'Medium'],
            ['task' => 'Clean room', 'priority' => 'Low'],
            ['task' => 'Review for exam', 'priority' => 'High'],
            ['task' => 'Pay bills', 'priority' => 'Medium'],
            ['task' => 'Workout', 'priority' => 'Low'],
            ['task' => 'Prepare presentation', 'priority' => 'High'],
            ['task' => 'Read book', 'priority' => 'Low'],
            ['task' => 'Fix bugs in code', 'priority' => 'High'],
            ['task' => 'Organize files', 'priority' => 'Medium'],
            ['task' => 'Attend meeting', 'priority' => 'High'],
            ['task' => 'Study AI concepts', 'priority' => 'Medium'],
            ['task' => 'Backup files', 'priority' => 'Low'],
            ['task' => 'Update resume', 'priority' => 'Medium'],
        ];

        foreach ($tasks as $index => $t) {
            $task = Task::create([
                'task' => $t['task'],
                'priority' => $t['priority'],
                'due_date' => Carbon::now()->addDays(rand(1, 10)),
                'status' => rand(0, 1),
                'list_id' => $lists->random()->id
            ]);

            if ($index % 4 === 0) {
                $task->delete(); // soft delete
            }
        }
    }
}