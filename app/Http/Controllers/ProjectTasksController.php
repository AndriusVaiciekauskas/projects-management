<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectTaskRequest;
use App\Http\Requests\UpdateProjectTaskRequest;
use App\Project;
use App\Task;

class ProjectTasksController extends Controller
{
    public function store(StoreProjectTaskRequest $request, Project $project)
    {
        $project->addTask($request->validated()['body']);

        return redirect()->route('projects.show', $project->id);
    }

    public function update(UpdateProjectTaskRequest $request, Project $project, Task $task)
    {
        $task->update(['body' => $request->get('body')]);

        $request->get('completed') ? $task->complete() : $task->incomplete();

        return redirect()->route('projects.show', $project->id);
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();
    }
}
