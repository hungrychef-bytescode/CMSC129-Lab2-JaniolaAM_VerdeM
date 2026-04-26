<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskList;

class TaskListSeeder extends Seeder
{
    public function run()
    {
        TaskList::insert([
            ['name' => 'Work'],
            ['name' => 'School'],
            ['name' => 'Personal'],
            ['name' => 'Urgent']
        ]);
    }
}