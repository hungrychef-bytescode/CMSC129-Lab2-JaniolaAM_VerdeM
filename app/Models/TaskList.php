<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $fillable = ['name'];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'list_id');
    }

    //archived task
    public function allTasks()
    {
        return $this->hasMany(Task::class, 'list_id')->withTrashed();
    }


    //Get all lists with ALL tasks (active + archived)
    public static function getAllWithAllTasks()
    {
        return self::with(['allTasks' => function ($query) {
            $query->latest();
        }])->get();
    }
}