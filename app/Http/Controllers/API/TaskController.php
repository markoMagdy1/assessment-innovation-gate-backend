<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Traits\ApiResponse;

class TaskController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $tasks = Task::where('assignee_id', Auth::id())->orWhere('creator_id', Auth::id())->orderBy('due_date', 'asc')->get();

        return $this->success($tasks, 'Tasks returned successfully', 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'nullable|in:low,medium,high',
            'assignee_email' => 'required|email|exists:users,email',
        ]);

        $assignee = User::where('email', $validated['assignee_email'])->first();

        $task = Task::create([
            'creator_id' => Auth::id(),
            'assignee_id' => $assignee->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'priority' => $validated['priority'],
        ]);

        return $this->success($task, 'Task created successfully', 201);
    }

    public function show($id)
    {
        $task = Task::with('assignee')->where('assignee_id', Auth::id())->findOrFail($id);
        return $this->success($task, 'Task returned successfully', 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::where(function ($q) {
            $q->where('assignee_id', Auth::id())->orWhere('creator_id', Auth::id());
        })->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|required|date',
            'priority' => 'nullable|in:low,medium,high',
            'is_completed' => 'nullable|boolean',
        ]);

        $task->update($validated);

        return response()->json(['message' => 'Task updated successfully', 'task' => $task]);
    }

    public function destroy($id)
    {
        $task = Task::where(function ($q) {
            $q->where('assignee_id', Auth::id())->orWhere('creator_id', Auth::id());
        })->findOrFail($id);

        $task->delete();
        return $this->success([], 'Task deleted successfully', 200);
    }

    public function toggleComplete($id)
    {
        $task = Task::where('assignee_id', Auth::id())->findOrFail($id);
        $task->is_completed = !$task->is_completed;
        $task->save();

        return response()->json(['message' => 'Task status updated', 'task' => $task]);
    }
}
