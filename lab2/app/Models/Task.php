<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'task',
        'priority',
        'due_date',
        'status',
        'list_id'
    ];

    public function list()
    {
        return $this->belongsTo(TaskList::class, 'list_id');
    }

     public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeArchived($query)
    {
        return $query->onlyTrashed();
    }
}