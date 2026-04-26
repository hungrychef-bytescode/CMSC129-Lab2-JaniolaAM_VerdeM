<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskList;

class TaskListController extends Controller
{
    //get all lists
    public function index()
    {
        $lists = TaskList::all();

        return view('lists.index', compact('lists'));
    }

    //create list
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        TaskList::create([
            'name' => $request->name
        ]);

        return back();
    }

    //delete list and all tasks under the list
    public function destroy(TaskList $list)
    {
        // delete all tasks under this list
        $list->tasks()->delete();

        // delete the list
        $list->delete();

        return back();
    }
}