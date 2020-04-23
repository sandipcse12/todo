<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        $count = Task::where('status', 'active')->count();
        $completed = Task::where('status', 'completed')->count();
        return response()->json(['tasks' => $tasks, 'count' => $count, 'completed' => $completed], 200);
    }

    public function save(Request $request)
    {
        $task = new Task();
        $task->title = $request->title;
        $task->status = 'active';
        $task->save();
        return response()->json(['message' => 'Task created successfully'], 201);
    }

    public function delete()
    {
        Task::where('status', 'completed')->delete();
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    public function completeTask(Request $request)
    {
        $id = $request->id;
        $task = Task::find($id);
        $task->status = 'completed';
        $task->save();
        return response()->json(['message' => 'Task Updated successfully'], 200);
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::find($id);
        $task->title = $request->title;
        $task->save();
        return response()->json(['message' => 'Task Updated successfully'], 200);

    }

    public function deleteTask($id)
    {
        Task::where('id', $id)->delete();
        return response()->json(['message' => 'Task deleted successfully'], 200);

    }
}
